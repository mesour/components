<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Session;

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
        $this->name = $section;
    }

    public function loadState($data)
    {
        $this->data = $data;
    }

    public function set($key, $val)
    {
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
