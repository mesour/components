<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components\BadStateException;
use Mesour\Components\Component;
use Mesour\Components\Helper;
use Mesour\Components\IComponent;
use Mesour\Components\InvalidArgumentException;
use Mesour\Components\Link\ILink;
use Mesour\Components\Link\IUrl;
use Mesour\Components\Link\Link;
use Mesour\Components\Localize\ITranslator;
use Mesour\Components\Localize\Translator;
use Mesour\Components\Security\Auth;
use Mesour\Components\Security\IAuth;
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
     * @var IAuth|null
     */
    static public $default_auth = NULL;

    /**
     * @var IAuth|null
     */
    private $auth = NULL;

    private $resource = NULL;

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

    public function attached(IComponent $parent)
    {
        parent::attached($parent);
        if ($app = $this->getApplication()) {
            $do = str_replace('m_', '', $app->getRequest()->get('m_do'));
            $handles = array();
            if (is_string($do)) {
                $handles[] = $do;
            } elseif (is_array($do)) {
                $handles = $do;
            }
            foreach ($handles as $key => $handle) {
                if ($this->getReflection()->hasMethod('handle' . $handle) && $this->getName() === $key) {
                    $request = $app->getRequest()->get('m_' . $key);

                    $method = $this->getReflection()->getMethod('handle' . $handle);
                    $parameters = $method->getParameters();
                    $args = array();
                    foreach ($parameters as $parameter) {
                        $name = $parameter->getName();
                        if ($parameter->isDefaultValueAvailable()) {
                            $default_value = $parameter->getDefaultValue();
                        }
                        if (isset($request[$name])) {
                            if (($parameter->isArray() || (isset($default_value) && is_array($default_value))) && !is_array($request[$name])) {
                                throw new BadStateException('Invalid request. Argument must be an array. ' . gettype($request[$name]) . '" given.');
                            }
                            $value = $request[$name];
                        } else {
                            if (isset($default_value)) {
                                $value = $default_value;
                            } else {
                                throw new BadStateException('Invalid request. Required parameter "' . $name . '" doest not exists.');
                            }
                        }
                        $args[] = $value;
                    }
                    Helper::invokeArgs(array($this, 'handle' . $handle), $args);
                } elseif ($this->getName() === $key) {
                    throw new BadStateException('Invalid request. No handler for "handle' . ucfirst($handle) . '".');
                } else {
                    throw new BadStateException('Invalid request. Component "' . $key . '" does not exists.');
                }
            }
        }
    }

    public function setResource($resource)
    {
        if (!is_string($resource) && !is_null($resource)) {
            throw new InvalidArgumentException('Resource must be string or NULL. ' . gettype($resource) . ' given.');
        }
        $this->resource = $resource;
        return $this;
    }

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
            if ($parent->getSession()) {
                $this->session = $parent->getSession()->getEmptyClone($this->getFullName());
                $this->session->loadState();
            }
            return $this->session;
        } else {
            return ($this->session
                ? $this->session
                : (self::$default_session ? self::$default_session->getEmptyClone($this->getFullName()) : NULL
                )
            );
        }
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->getParent() instanceof Application ? $this->getParent() : ($this->getParent() ? $this->getParent()->getApplication() : null);
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
                : (self::$default_translator ? self::$default_translator : (self::$default_translator = new Translator)
                )
            );
    }

    public function setAuth(IAuth $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    public function create()
    {
        return '';
    }

    public function render()
    {
        if ($this->getSession()) {
            $this->getSession()->saveState();
        }
        echo $this->create();
    }

    /**
     * @return IAuth
     */
    public function getAuth()
    {
        $parent = $this->getParent();
        $this->auth = !$this->auth && $parent instanceof self
            ? $parent->getAuth()
            : ($this->auth
                ? $this->auth
                : (self::$default_auth ? self::$default_auth : (self::$default_auth = new Auth)
                )
            );
        $this->auth->setResource($this->resource);
        return $this->auth;
    }

}
