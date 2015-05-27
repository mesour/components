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
use Mesour\Components\Session\ISessionSection;
use Nette\Http;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class SessionSection implements ISessionSection
{

    /**
     * @var Http\SessionSection
     */
    private $sessionSection;

    public function __construct(Http\SessionSection $sessionSection)
    {
        $this->sessionSection = $sessionSection;
    }

    public function remove()
    {
        $this->sessionSection->remove();
    }

    public function set($key, $val)
    {
        if (!Helper::validateKeyName($key)) {
            throw new InvalidArgumentException('SessionSection name must be integer or string, ' . gettype($key) . ' given.');
        }
        $this->sessionSection[$key] = $val;
        return $this;
    }

    public function get($key = NULL, $default = NULL)
    {
        if (!isset($this->sessionSection[$key])) {
            return $default;
        }
        return $this->sessionSection[$key];
    }

    public function loadState($data)
    {
        //do nothing, loaded by nette
    }

}
