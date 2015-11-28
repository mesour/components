<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
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

    /**
     * Converts to ASCII.
     * @param  string  UTF-8 encoding
     * @return string  ASCII
     */
    public static function toAscii($s)
    {
        $s = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{2FF}\x{370}-\x{10FFFF}]#u', '', $s);
        $s = strtr($s, '`\'"^~?', "\x01\x02\x03\x04\x05\x06");
        $s = str_replace(
            ["\xE2\x80\x9E", "\xE2\x80\x9C", "\xE2\x80\x9D", "\xE2\x80\x9A", "\xE2\x80\x98", "\xE2\x80\x99", "\xC2\xB0"],
            ["\x03", "\x03", "\x03", "\x02", "\x02", "\x02", "\x04"], $s
        );
        if (class_exists('Transliterator') && $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII')) {
            $s = $transliterator->transliterate($s);
        }
        if (ICONV_IMPL === 'glibc') {
            $s = str_replace(
                ["\xC2\xBB", "\xC2\xAB", "\xE2\x80\xA6", "\xE2\x84\xA2", "\xC2\xA9", "\xC2\xAE"],
                ['>>', '<<', '...', 'TM', '(c)', '(R)'], $s
            );
            $s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT//IGNORE', $s); // intentionally @
            $s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
                . "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
                . "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
                . "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe"
                . "\x96\xa0\x8b\x97\x9b\xa6\xad\xb7",
                "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt- <->|-.");
            $s = preg_replace('#[^\x00-\x7F]++#', '', $s);
        } else {
            $s = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s); // intentionally @
        }
        $s = str_replace(['`', "'", '"', '^', '~', '?'], '', $s);
        return strtr($s, "\x01\x02\x03\x04\x05\x06", '`\'"^~?');
    }

    /**
     * Converts to web safe characters [a-z0-9-] text.
     * @param  string  UTF-8 encoding
     * @param  string  allowed characters
     * @param  bool
     * @return string
     */
    public static function webalize($s, $charlist = NULL, $lower = TRUE)
    {
        $s = self::toAscii($s);
        if ($lower) {
            $s = strtolower($s);
        }
        $s = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $s);
        $s = trim($s, '-');
        return $s;
    }

    static public function matchAll($subject, $pattern, $flags = 0, $offset = 0)
    {
        if ($offset > strlen($subject)) {
            return [];
        }
        $m = [];
        preg_match_all(
            $pattern, $subject, $m,
            ($flags & PREG_PATTERN_ORDER) ? $flags : ($flags | PREG_SET_ORDER),
            $offset
        );
        return $m;
    }

    static public function parseValue($value, $data)
    {
        if (
            (is_array($data) || $data instanceof \ArrayAccess || is_object($data))
            && strpos($value, '{') !== FALSE
            && strpos($value, '}') !== FALSE
        ) {
            return preg_replace_callback('/(\{[^\{]+\})/', function ($matches) use ($value, $data) {
                $matches = array_unique($matches);
                $match = reset($matches);
                $key = substr($match, 1, strlen($match) - 2);
                if(is_object($data)) {
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

    /**
     * Invokes internal PHP function with own error handler.
     * @author David Grudl (http://davidgrudl.com)
     * @return mixed
     */
    public static function invokeSafe($function, array $args, $onError)
    {
        $prev = set_error_handler(function($severity, $message, $file) use ($onError, & $prev) {
            if ($file === __FILE__ && $onError($message, $severity) !== FALSE) {
                return;
            } elseif ($prev) {
                return call_user_func_array($prev, func_get_args());
            }
            return FALSE;
        });
        try {
            $res = call_user_func_array($function, $args);
            restore_error_handler();
            return $res;
        } catch (\Exception $e) {
            restore_error_handler();
            throw $e;
        }
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

    static public function arrayContains($str, array $arr)
    {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false) return true;
        }
        return false;
    }

    static public function isDateHasTime($php_format)
    {
        return self::arrayContains($php_format, ['a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u']);
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
            'u' => ''
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
