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
class Session implements ISession
{

    /**
     * @var string
     */
    private $section;

    /**
     * @var array
     */
    private $session = array();

    public function __construct($section)
    {
        self::sessionStart();
        $this->loadState();
        $this->section = $section;
    }

    public function getEmptyClone($section)
    {
        return new self($section);
    }

    public function set($key, $val)
    {
        $_SESSION[__NAMESPACE__][$this->section][$key] = $val;
    }

    public function get($key)
    {
        return $_SESSION[__NAMESPACE__][$this->section][$key];
    }

    public function deleteAll()
    {
        $_SESSION[__NAMESPACE__] = NULL;
    }

    public function loadState()
    {
        $this->session = isset($_SESSION[__NAMESPACE__][$this->section]) ? $_SESSION[__NAMESPACE__][$this->section] : array();
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

    static private function sessionStart()
    {
        if (session_id() == '') {
            session_start();
        }
    }

}
