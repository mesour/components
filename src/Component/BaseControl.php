<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components;

use Mesour\Components\Application;
use Mesour\UI\Control;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
abstract class BaseControl extends Container implements IString
{

    const SNIPPET_PREFIX = 'm_snippet-';

    /**
     * @var Security\IAuth|null
     */
    private $auth = NULL;

    /**
     * @var Session\ISession|null
     */
    private $session = NULL;

    /**
     * @var Application\IPayload
     */
    private $payload = NULL;

    /**
     * @var Localize\ITranslator|null
     */
    private $translator = NULL;

    /**
     * @var Link\ILink|null
     */
    private $link;

    public function beforeRender()
    {
        if ($app = $this->getApplication(FALSE)) {
            /** @var \Mesour\UI\Application $app */
            $do = str_replace('m_', '', $app->getRequest()->get('m_do'));
            if (strlen($do) > 0) {
                $exploded = array_filter(explode('-', $do));
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
                        Helper::invokeArgs(array($this, 'handle' . $handle), $args);
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

    public function setLink(Link\ILink $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @param $destination
     * @param array $args
     * @return Link\IUrl
     */
    public function link($destination, $args = array())
    {
        return $this->getLink()->create($destination, $args);
    }

    /**
     * @return Link\ILink
     */
    public function getLink()
    {
        $parent = $this->getParent();
        if (!$this->link) {
            if ($parent instanceof self) {
                return $parent->getLink();
            } else {
                return $this->link = new Link\Link;
            }
        }
        return $this->link;
    }

    public function setPayload(Application\IPayload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return Application\IPayload
     */
    public function getPayload()
    {
        $parent = $this->getParent();
        if (!$this->payload) {
            if ($parent instanceof self) {
                return $parent->getPayload();
            } else {
                return $this->payload = new Application\Payload;
            }
        }
        return $this->payload;
    }

    public function setSession(Session\ISession $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return Session\ISession
     */
    public function getSession()
    {
        $parent = $this->getParent();
        if (!$this->session) {
            if ($parent instanceof self) {
                return $parent->getSession();
            } else {
                return $this->session = new Session\Session;
            }
        }
        return $this->session;
    }

    /**
     * @param bool $need
     * @return Application\IApplication|null
     * @throws BadStateException
     */
    public function getApplication($need = TRUE)
    {
        if ($this instanceof Application\IApplication) {
            return $this;
        }
        $parent = $this->getParent();
        if ($parent instanceof Application\IApplication) {
            return $parent;
        }
        if (!$parent) {
            if ($need) {
                throw new BadStateException('Component ' . $this->getName() . ' is not attached to Application.');
            }
            return NULL;
        } else {
            return $this->getParent()->getApplication($need);
        }
    }

    public function setTranslator(Localize\ITranslator $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return Localize\ITranslator
     */
    public function getTranslator()
    {
        $parent = $this->getParent();
        if (!$this->translator) {
            if ($parent instanceof self) {
                return $parent->getTranslator();
            } else {
                return $this->translator = new Localize\Translator;
            }
        }
        return $this->translator;
    }

    public function setAuth(Security\IAuth $auth)
    {
        $this->auth = $auth;
        return $this;
    }

    public function createSnippet()
    {
        $name = self::SNIPPET_PREFIX . $this->createLinkName();
        //$this->getApplication()->setSnippet($name, $this);
        return Html::el('div', array('id' => $name));
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
     * @return Security\IAuth
     */
    public function getAuth()
    {
        $parent = $this->getParent();
        if (!$this->auth) {
            if ($parent instanceof self) {
                return $parent->getAuth();
            } else {
                return $this->auth = new Security\Auth;
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
