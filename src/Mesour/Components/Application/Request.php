<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Application;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Request
{

    private $request;

    private $headers = [];

    public function __construct(array $request)
    {
        $this->headers = $this->getAllHeaders();
        $this->request = $request;
    }

    public function getHeader($name, $default = NULL)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : $default;
    }

    public function get($key = NULL, $default = NULL)
    {
        if (is_null($key)) {
            return $this->request;
        }
        return isset($this->request[$key]) ? $this->request[$key] : $default;
    }

    protected function getAllHeaders()
    {
        if (!function_exists('getallheaders')) {
            $headers = '';
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
        return getallheaders();
    }

}
