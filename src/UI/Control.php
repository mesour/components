<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components\Application\IPayload;
use Mesour\Components\Application\Payload;
use Mesour\Components\BadStateException;
use Mesour\Components\Component;
use Mesour\Components\Helper;
use Mesour\Components\Html;
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

    const SNIPPET_PREFIX = 'm_snippet-';

    /**
     * @var ILink|null
     */
    static public $default_link = NULL;
    /**
     *
     * @var IPayload|null
     */
    static public $default_payload = NULL;

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
     * @var IPayload
     */
    private $payload = NULL;

    /**
     * @var ITranslator|null
     */
    private $translator = NULL;

    /**
     * @var ILink|null
     */
    private $link;

    protected function beforeRender()
    {
        if ($app = $this->getApplication()) {
            $do = str_replace('m_', '', $app->getRequest()->get('m_do'));
            if (strlen($do) > 0) {
                $exploded = explode('-', $do);
                $current = NULL;
                $x = 0;
                $handle = NULL;
                foreach ($exploded as $item) {
                    if ($x === 0) {
                        $current = $app[$item];
                    } elseif ($x < count($exploded)) {
                        if (!isset($current[$item])) {
                            $handle = $item;
                            break;
                        }
                        $current = $current[$item];
                    }
                    $x++;
                }
                if ($handle) {
                    if ($current && $current->getReflection()->hasMethod('handle' . $handle) && $this === $current) {
                        $method = $this->getReflection()->getMethod('handle' . $handle);
                        $parameters = $method->getParameters();
                        $args = array();
                        foreach ($parameters as $parameter) {
                            $name = $parameter->getName();
                            if ($parameter->isDefaultValueAvailable()) {
                                $default_value = $parameter->getDefaultValue();
                            }
                            $parsed_name = $current->createLinkName() . '-' . $name;
                            if (!is_null($_value = $app->getRequest()->get('m_' . $parsed_name))) {
                                if (($parameter->isArray() || (isset($default_value) && is_array($default_value))) && !is_array($_value)) {
                                    throw new BadStateException('Invalid request. Argument must be an array. ' . gettype($_value) . '" given.');
                                }
                                $value = $_value;
                            } else {
                                if (isset($default_value)) {
                                    $value = $default_value;
                                } else {
                                    throw new BadStateException('Invalid request. Required parameter "' . $parsed_name . '" doest not exists.');
                                }
                            }
                            $args[] = $value;
                        }
                        Helper::invokeArgs(array($this, 'handle' . $handle), $args);
                    } elseif ($this === $current) {
                        throw new BadStateException('Invalid request. No handler for "handle' . ucfirst($handle) . '".');
                    }
                }
            }
        }
    }

    public function createLink($handle, $args = array())
    {
        return $this->getApplication()->createLink($this, $handle, $args);
    }

    public function createLinkName()
    {
        return $this->getParent() instanceof self ? $this->getParent()->createLinkName() . '-' . $this->getName() : $this->getName();
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

    public function setPayload(IPayload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function createSnippet()
    {
        $name = self::SNIPPET_PREFIX . $this->createLinkName();
        //$this->getApplication()->setSnippet($name, $this);
        return Html::el('div', array('id' => $name));
    }

    /**
     * @return IPayload
     */
    public function getPayload()
    {
        $parent = $this->getParent();
        return !$this->payload && $parent instanceof self
            ? $parent->getPayload()
            : ($this->payload
                ? $this->payload
                : (self::$default_payload ? self::$default_payload : (self::$default_payload = new Payload)
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
        $this->beforeRender();
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
