<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

use Mesour\Components\Session\ISession;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
abstract class SessionStatement extends Statement
{

    /**
     * @var ISession
     */
    private $session;

    /**
     * @return ISession
     * @throws InvalidArgumentException
     */
    public function getSession()
    {
        if (!$this->session) {
            throw new InvalidArgumentException('Session is not set.');
        }
        return $this->session;
    }

    /**
     * @param ISession $session
     */
    public function setSession(ISession $session)
    {
        $this->session = $session;
        $this->session->loadState();
    }

    public function __destruct()
    {
        $this->getSession()->saveState();
    }

}
