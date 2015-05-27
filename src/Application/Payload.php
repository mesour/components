<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

use Mesour\Components\InvalidArgumentException;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Payload implements IPayload
{

    private $data = array();

    public function sendPayload()
    {
        ob_clean();
        header('Content-type: application/json');
        echo json_encode($this->data);
        exit(0);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    public function set($key, $value)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('Key must be string. ' . gettype($key) . ' given.');
        }
        $this->data[$key] = $value;
        return $this;
    }

    public function get($key = NULL, $default = NULL)
    {
        if (is_null($key)) {
            return $this->data;
        }
        if (!is_string($key)) {
            throw new InvalidArgumentException('Key must be string. ' . gettype($key) . ' given.');
        }
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

}
