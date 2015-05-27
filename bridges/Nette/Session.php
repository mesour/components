<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Bridges\Nette;

use Mesour\Components\Helper;
use Mesour\Components\InvalidArgumentException;
use Mesour\Components\Session\ISession;
use Mesour\Components\Session\ISessionSection;
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

    private $sections = array();

    public function __construct(Http\Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param $section
     * @return ISessionSection
     * @throws InvalidArgumentException
     */
    public function getSection($section)
    {
        if (!Helper::validateKeyName($section)) {
            throw new InvalidArgumentException('SessionSection name must be integer or string, ' . gettype($section) . ' given.');
        }
        $this->sections[$section] = $section;
        return new SessionSection($this->session->getSection($section));
    }

    public function hasSection($section)
    {
        return isset($this->sections[$section]);
    }

    public function destroy()
    {
        $this->session->destroy();
    }

    public function loadState()
    {

    }

    public function saveState()
    {

    }

}
