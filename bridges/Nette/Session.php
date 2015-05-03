<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Bridges\Nette;

use Mesour\Components\Session\ISession;
use Nette\Http;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Session implements ISession
{

    /**
     * @var Http\Session
     */
    private $session;

    private $section;

    /**
     * @var Http\SessionSection
     */
    private $sessionSection;

    public function __construct($section, Http\Session $session) {
        $this->session = $session;
        $this->section = $section;
        $this->sessionSection = $session->getSection($section);
    }

    public function getEmptyClone($section) {
        return new self($section, $this->session);
    }

    public function set($key, $val)
    {
        $this->sessionSection[$key] = $val;
    }

    public function get($key)
    {
        return $this->sessionSection[$key];
    }

    public function deleteAll()
    {
        $this->sessionSection->remove();
    }

    public function loadState()
    {
        //do nothing, loaded by Nette session
    }

    public function saveState()
    {
        //do nothing, loaded by Nette session
    }

    /**
     * @return Http\SessionSection
     */
    public function getSection() {
        return $this->sessionSection;
    }

}
