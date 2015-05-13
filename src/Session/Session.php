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
     * @var array
     */
    private $sections;

    /**
     * @var array
     */
    private $session = array();

    private $loaded = FALSE;

    public function __construct()
    {
        self::sessionStart();
        $this->loadState();
    }

    /**
     * @param $section
     * @return ISessionSection
     */
    public function getSection($section)
    {
        if (!$this->sections[$section]) {
            $this->sections[$section] = $session_section = new SessionSection($section);
            $session_section->loadState(isset($this->session[$section]) ? $this->session[$section] : array());
        }
        return $this->sections[$section];
    }

    public function remove()
    {
        unset($_SESSION[__NAMESPACE__]);
    }

    public function loadState()
    {
        if (!$this->loaded) {
            $this->loaded = TRUE;
            $this->session = isset($_SESSION[__NAMESPACE__]) ? $_SESSION[__NAMESPACE__] : array();
        }
    }

    public function saveState()
    {
        foreach ($this->sections as $name => $section) {
            /** @var ISessionSection $section */
            $data = $section->get();
            $this->session[$name] = $data;
        }

        $_SESSION[__NAMESPACE__] = $this->session;
    }

    static private function sessionStart()
    {
        if (session_id() == '') {
            session_start();
        }
    }

}
