<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components\Component;
use Mesour\Components\Link\ILink;
use Mesour\Components\Localize\ITranslator;
use Mesour\Components\Security\IAuth;
use Mesour\Components\Session\ISession;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Application extends Component
{

    public function setSession(ISession $session)
    {
        Control::$default_session = $session;
    }

    public function setLink(ILink $link)
    {
        Control::$default_link = $link;
    }

    public function setTranslator(ITranslator $translator)
    {
        Control::$default_translator = $translator;
    }

    public function setAuth(IAuth $auth)
    {
        Control::$default_auth = $auth;
    }

}
