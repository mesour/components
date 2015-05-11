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

}
