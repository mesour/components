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
class Session implements ISession
{

    /**
     * @var array
     */
    private $sections = array();

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
     * @throws InvalidArgumentException
     */
    public function getSection($section)
    {
        if (!$this->hasSection($section)) {
            $this->sections[$section] = $session_section = new SessionSection($section);
            $session_section->loadState(isset($this->session[$section]) ? $this->session[$section] : array());
        }
        return $this->sections[$section];
    }

    public function hasSection($section)
    {
        if (!Helper::validateKeyName($section)) {
            throw new InvalidArgumentException('SessionSection name must be integer or string, ' . gettype($section) . ' given.');
        }
        return isset($this->sections[$section]);
    }

    public function destroy()
    {
        unset($_SESSION[__NAMESPACE__]);
        return $this;
    }

    public function loadState()
    {
        if (!$this->loaded) {
            $this->loaded = TRUE;
            $this->session = isset($_SESSION[__NAMESPACE__]) ? $_SESSION[__NAMESPACE__] : array();
        }
        return $this;
    }

    public function saveState()
    {
        foreach ($this->sections as $name => $section) {
            /** @var ISessionSection $section */
            $data = $section->get();
            $this->session[$name] = $data;
        }
        $_SESSION[__NAMESPACE__] = $this->session;
        return $this;
    }

    static private function sessionStart()
    {
        if (session_id() == '') {
            session_start();
        }
    }

}
