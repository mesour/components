<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components\BadStateException;
use Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 *
 * @method Control getParent()
 */
abstract class BaseControl extends Components\Container implements Components\IString
{

    const SNIPPET_PREFIX = 'm_snippet-';

    /**
     * @var Components\Security\IAuth|null
     */
    private $auth = NULL;

    /**
     * @var Components\Session\ISession|null
     */
    private $session = NULL;

    /**
     * @var Components\Application\IPayload
     */
    private $payload = NULL;

    /**
     * @var Components\Localize\ITranslator|null
     */
    private $translator = NULL;

    /**
     * @var Components\Link\ILink|null
     */
    private $link;

    public function beforeRender()
    {
        if ($app = $this->getApplication(FALSE)) {
            /** @var Application $app */
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
                    /** @var Control $current */
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
                        Components\Helper::invokeArgs(array($this, 'handle' . $handle), $args);
                        $this->getSession()->saveState();
                    } elseif ($this === $current) {
                        throw new BadStateException('Invalid request. No handler for "handle' . ucfirst($handle) . '".');
                    }
                }
            }
        }
    }

    public function createLinkName()
    {
        return $this->getParent() instanceof self ? $this->getParent()->createLinkName() . '-' . $this->getName() : $this->getName();
    }

    public function setLink(Components\Link\ILink $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @param $destination
     * @param array $args
     * @return Components\Link\IUrl
     */
    public function link($destination, $args = array())
    {
        return $this->getLink()->create($destination, $args);
    }

    /**
     * @return Components\Link\ILink
     */
    public function getLink()
    {
        $parent = $this->getParent();
        if (!$this->link) {
            if ($parent instanceof self) {
                return $parent->getLink();
            } else {
                return $this->link = new Components\Link\Link;
            }
        }
        return $this->link;
    }

    public function setPayload(Components\Application\IPayload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return Components\Application\IPayload
     */
    public function getPayload()
    {
        $parent = $this->getParent();
        if (!$this->payload) {
            if ($parent instanceof self) {
                return $parent->getPayload();
            } else {
                return $this->payload = new Components\Application\Payload;
            }
        }
        return $this->payload;
    }

    public function setSession(Components\Session\ISession $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return Components\Session\ISession
     */
    public function getSession()
    {
        $parent = $this->getParent();
        if (!$this->session) {
            if ($parent instanceof self) {
                return $parent->getSession();
            } else {
                return $this->session = new Components\Session\Session;
            }
        }
        return $this->session;
    }

    /**
     * @param bool $need
     * @return Components\Application\IApplication|null
     * @throws BadStateException
     */
    public function getApplication($need = TRUE)
    {
        if($this instanceof Components\Application\IApplication) {
            return $this;
        }
        $parent = $this->getParent();
        if($parent instanceof Components\Application\IApplication) {
            return $parent;
        }
        if(!$parent) {
            if($need) {
                throw new BadStateException('Component ' . $this->getName() . ' is not attached to Application.');
            }
            return NULL;
        } else {
            return $this->getParent()->getApplication($need);
        }
    }

    public function setTranslator(Components\Localize\ITranslator $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return Components\Localize\ITranslator
     */
    public function getTranslator()
    {
        $parent = $this->getParent();
        if (!$this->translator) {
            if ($parent instanceof self) {
                return $parent->getTranslator();
            } else {
                return $this->translator = new Components\Localize\Translator;
            }
        }
        return $this->translator;
    }

    public function setAuth(Components\Security\IAuth $auth)
    {
        $this->auth = $auth;
        return $this;
    }

    public function createSnippet()
    {
        $name = self::SNIPPET_PREFIX . $this->createLinkName();
        //$this->getApplication()->setSnippet($name, $this);
        return Components\Html::el('div', array('id' => $name));
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
     * @return Components\Security\IAuth
     */
    public function getAuth()
    {
        $parent = $this->getParent();
        if (!$this->auth) {
            if ($parent instanceof self) {
                return $parent->getAuth();
            } else {
                return $this->auth = new Components\Security\Auth;
            }
        }
        return $this->auth;
    }

    public function __toString()
    {
        $this->render();
        return '';
    }

}
