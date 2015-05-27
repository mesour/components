<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Helper
{

    /**
     * @throws StaticClassException
     */
    public function __construct()
    {
        throw new StaticClassException;
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
     * @throws InvalidArgumentException
     */
    static public function validateComponentName($name, $need = TRUE)
    {
        $valid = TRUE;

        if (!self::validateKeyName($name) || !preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            $valid = FALSE;
        }

        if ($need && !$valid) {
            throw new InvalidArgumentException('Component name must be integer or alphanumeric string, ' . gettype($name) . ' given.');
        }

        return $valid;
    }

    static public function matchAll($subject, $pattern, $flags = 0, $offset = 0)
    {
        if ($offset > strlen($subject)) {
            return array();
        }
        $m = array();
        preg_match_all(
            $pattern, $subject, $m,
            ($flags & PREG_PATTERN_ORDER) ? $flags : ($flags | PREG_SET_ORDER),
            $offset
        );
        return $m;
    }

    static public function parseValue($value, $data)
    {
        if ((is_array($data) || $data instanceof \ArrayAccess) && strpos($value, '{') !== FALSE && strpos($value, '}') !== FALSE) {
            return preg_replace_callback('/(\{[^\{]+\})/', function ($matches) use ($value, $data) {
                $matches = array_unique($matches);
                $match = reset($matches);
                $key = substr($match, 1, strlen($match) - 2);
                return isset($data[$key]) ? $data[$key] : '__UNDEFINED_KEY-' . $key . '__';
            }, $value);
        } else {
            return $value;
        }
    }

    static public function createAttribute(array & $attributes, $key, $value, $append = FALSE)
    {
        if ($append && isset($attributes[$key])) {
            $attributes[$key] = $attributes[$key] . ' ' . $value;
        } else {
            $attributes[$key] = $value;
        }
        return $attributes;
    }

    static public function checkCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Argument is not callable.');
        }
    }

    static public function invokeArgs($callback, $args)
    {
        self::checkCallback($callback);
        return call_user_func_array($callback, $args);
    }

    static public function setOpt(&$array_ptr, $key, $value, $separator = '.')
    {
        $keys = explode($separator, $key);
        // extract the last key
        $last_key = array_pop($keys);

        // make sure the array is built up
        while ($arr_key = array_shift($keys)) {
            if (!array_key_exists($arr_key, $array_ptr)) {
                $array_ptr[$arr_key] = array();
            }
            $array_ptr = &$array_ptr[$arr_key];
        }
        $array_ptr[$last_key] = $value;
    }

    static public function convertDateToJsFormat($php_format)
    {
        $symbols = array(
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
            'u' => ''
        );
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
