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

    public function loadState($data) {
        $this->data = $data;
    }

    public function set($key, $val)
    {
        $this->session[$key] = $val;
    }

    public function get($key, $default = NULL)
    {
        return !isset($this->session[$key]) ? $default : $this->session[$key];
    }

    public function deleteAll()
    {
        $_SESSION[__NAMESPACE__] = NULL;
    }

    public function saveState()
    {
        $_SESSION[__NAMESPACE__][$this->section] = $this->session;
        session_write_close();
    }

    /**
     * @return array
     */
    public function getSection()
    {
        return $this->session;
    }

    public function __destruct() {
        $this->saveState();
    }

    static private function sessionStart()
    {
        if (session_id() == '') {
            session_start();
        }
    }

}
