<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

use Mesour\Components\Link\ILink;
use Mesour\Components\Localize\ITranslator;
use Mesour\Components\Security\IAuth;
use Mesour\Components\Session\ISession;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Request
{

    private $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function get($key, $default = NULL)
    {
        return isset($this->request[$key]) ? $this->request[$key] : $default;
    }

}
