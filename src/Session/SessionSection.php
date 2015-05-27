<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Session;
use Mesour\Components\Helper;
use Mesour\Components\InvalidArgumentException;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class SessionSection implements ISessionSection
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data = array();

    public function __construct($section)
    {
        if (!Helper::validateKeyName($section)) {
            throw new InvalidArgumentException('SessionSection name must be integer or string, ' . gettype($section) . ' given.');
        }
        $this->name = $section;
    }

    public function loadState($data)
    {
        $this->data = $data;
    }

    public function set($key, $val)
    {
        if (!Helper::validateKeyName($key)) {
            throw new InvalidArgumentException('Key must be integer or string, ' . gettype($key) . ' given.');
        }
        $this->data[$key] = $val;
    }

    public function get($key = NULL, $default = NULL)
    {
        if (is_null($key)) {
            return $this->data;
        }
        return !isset($this->data[$key]) ? $default : $this->data[$key];
    }

    public function remove()
    {
        $this->data = array();
    }

}
