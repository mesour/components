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
use Mesour\Components\Link\IUrl;
use Mesour\Components\Link\Link;
use Mesour\Components\Localize\ITranslator;
use Mesour\Components\Localize\Translator;
use Mesour\Components\Session\ISession;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
abstract class Control extends Component
{

    /**
     * @var ILink|null
     */
    static public $default_link = NULL;

    /**
     * @var ISession|null
     */
    static public $default_session = NULL;

    /**
     * @var ITranslator|null
     */
    static public $default_translator = NULL;

    /**
     * @var ISession|null
     */
    private $session = NULL;

    /**
     * @var ITranslator|null
     */
    private $translator = NULL;

    /**
     * @var ILink|null
     */
    private $link;

    public function setLink(ILink $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @param $destination
     * @param array $args
     * @return IUrl
     */
    public function link($destination, $args = array())
    {
        return $this->getLink()->create($destination, $args);
    }

    /**
     * @return ILink
     */
    private function getLink()
    {
        $parent = $this->getParent();
        return !$this->link && $parent instanceof self
            ? $parent->getLink()
            : ($this->link
                ? $this->link
                : (self::$default_link
                    ? self::$default_link
                    : (self::$default_link = new Link)
                )
            );
    }

    public function setSession(ISession $session)
    {
        $this->session = $session;
        $this->session->loadState();
        return $this;
    }

    /**
     * @return ISession|null
     */
    public function getSession()
    {
        $parent = $this->getParent();
        if (!$this->session && $parent instanceof self) {
            $this->session = $parent->getSession()->getEmptyClone($this->getFullName());
            $this->session->loadState();
            return $this->session;
        } else {
            return ($this->session
                ? $this->session
                : (self::$default_session ? self::$default_session->getEmptyClone($this->getFullName()) : NULL
                )
            );
        }
    }

    public function setTranslator(ITranslator $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return ITranslator
     */
    public function getTranslator()
    {
        $parent = $this->getParent();
        return !$this->session && $parent instanceof self
            ? $parent->getTranslator()
            : ($this->session
                ? $this->session
                : (self::$default_translator ? self::$default_translator : (self::$default_link = new Translator)
                )
            );
    }

}
