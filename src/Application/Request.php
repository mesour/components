<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Request
{

    private $request;
    private $headers = array();

    public function __construct(array $request)
    {
        $this->headers = getallheaders();
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

}
