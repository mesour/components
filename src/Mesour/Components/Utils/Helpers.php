<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Utils;

use Mesour;
use Nette;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Helpers
{

	/**
	 * @throws Mesour\StaticClassException
	 */
	public function __construct()
	{
		throw new Mesour\StaticClassException;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	static public function validateKeyName($name)
	{
		return is_string($name) || is_int($name);
	}

	/**
	 * @param $name
	 * @param bool $need
	 * @return bool
	 * @throws Mesour\InvalidArgumentException
	 */
	static public function validateComponentName($name, $need = true)
	{
		$valid = true;

		if (!self::validateKeyName($name) || !preg_match('/^[A-Za-z0-9_]+$/', $name)) {
			$valid = false;
		}

		if ($need && !$valid) {
			throw new Mesour\InvalidArgumentException(
				sprintf('Component name must be integer or alphanumeric string, %s given.', gettype($name))
			);
		}

		return $valid;
	}

	/**
	 * Converts to ASCII.
	 * @param  string $s UTF-8 encoding
	 * @return string  ASCII
	 */
	public static function toAscii($s)
	{
		return Nette\Utils\Strings::toAscii($s);
	}

	/**
	 * Converts to web safe characters [a-z0-9-] text.
	 * @param  string $s UTF-8 encoding
	 * @param  string $charList allowed characters
	 * @param  bool $lower
	 * @return string
	 */
	public static function webalize($s, $charList = null, $lower = true)
	{
		return Nette\Utils\Strings::webalize($s, $charList, $lower);
	}

	static public function matchAll($subject, $pattern, $flags = 0, $offset = 0)
	{
		return Nette\Utils\Strings::matchAll($subject, $pattern, $flags, $offset);
	}

	static public function parseValue($value, $data)
	{
		if (
			(is_array($data) || $data instanceof \ArrayAccess || is_object($data))
			&& strpos($value, '{') !== false
			&& strpos($value, '}') !== false
		) {
			return preg_replace_callback('/(\{[^\{]+\})/', function ($matches) use ($value, $data) {
				$matches = array_unique($matches);
				$match = reset($matches);
				$key = substr($match, 1, strlen($match) - 2);
				if (is_object($data)) {
					$currentValue = isset($data->{$key}) ? $data->{$key} : '__UNDEFINED_KEY-' . $key . '__';
				} else {
					$currentValue = isset($data[$key]) ? $data[$key] : '__UNDEFINED_KEY-' . $key . '__';
				}

				return $currentValue;
			}, $value);
		} else {
			return $value;
		}
	}

	static public function createAttribute(array & $attributes, $key, $value, $append = false)
	{
		if ($append && isset($attributes[$key])) {
			$attributes[$key] = $attributes[$key] . ' ' . $value;
		} else {
			$attributes[$key] = $value;
		}
		return $attributes;
	}

	/**
	 * @param $callable
	 * @param bool|FALSE $syntax
	 * @return callable
	 */
	static public function checkCallback($callable, $syntax = false)
	{
		return Nette\Utils\Callback::check($callable, $syntax);
	}

	static public function invokeArgs($callable, array $args = [])
	{
		return Nette\Utils\Callback::invokeArgs($callable, $args);
	}

	static public function setOpt(&$array_ptr, $key, $value, $separator = '.')
	{
		$keys = explode($separator, $key);
		$last_key = array_pop($keys);

		while ($arr_key = array_shift($keys)) {
			if (!array_key_exists($arr_key, $array_ptr)) {
				$array_ptr[$arr_key] = [];
			}
			$array_ptr = &$array_ptr[$arr_key];
		}
		$array_ptr[$last_key] = $value;
	}

	static public function containsStringFromArray($str, array $arr)
	{
		foreach ($arr as $a) {
			if (stripos($str, $a) !== false) return true;
		}
		return false;
	}

	/**
	 * @param $phpFormat
	 * @return bool
	 * @deprecated
	 */
	static public function isDateHasTime($phpFormat)
	{
		return self::isDateAlsoContainsTime($phpFormat);
	}

	/**
	 * @param $phpFormat
	 * @return bool
	 * @deprecated
	 */
	static public function isDateAlsoContainsTime($phpFormat)
	{
		return self::containsStringFromArray($phpFormat, ['a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u']);
	}

	static public function convertDateToJsFormat($php_format)
	{
		$symbols = [
			// Day
			'd' => 'DD',
			'D' => 'ddd',
			'j' => 'D',
			'l' => 'dddd',
			'N' => 'E',
			'S' => '',
			'w' => 'e',
			'z' => 'DDD',
			// Week
			'W' => 'W',
			// Month
			'F' => 'MMMM',
			'm' => 'MM',
			'M' => 'MMM',
			'n' => 'M',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'YYYY',
			'y' => 'YY',
			// Time
			'a' => 'a',
			'A' => 'A',
			'B' => 'SSS',
			'g' => 'h',
			'G' => 'H',
			'h' => 'hh',
			'H' => 'HH',
			'i' => 'mm',
			's' => 'ss',
			'u' => '',
		];
		$js_format = "";
		$escaping = false;
		for ($i = 0; $i < strlen($php_format); $i++) {
			$char = $php_format[$i];
			if ($char === '\\') // PHP date format escaping character
			{
				$i++;
				if ($escaping) $js_format .= $php_format[$i];
				else $js_format .= '\'' . $php_format[$i];
				$escaping = true;
			} else {
				if ($escaping) {
					$js_format .= "'";
					$escaping = false;
				}
				if (isset($symbols[$char]))
					$js_format .= $symbols[$char];
				else
					$js_format .= $char;
			}
		}
		return $js_format;
	}

}
