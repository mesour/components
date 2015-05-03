<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

use Mesour\Components\Session\ISession;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
abstract class Component implements IComponent, IObserver
{

    private $name;

    /**
     * @var ISession
     */
    protected $session;

    /**
     * @var Component
     */
    private $parent;

    /**
     * @var IContainer
     */
    protected $container;

    public function __construct($name, IComponent $parent = NULL)
    {
        $this->container = new Container();

        if (is_string($name)) {
            $this->name = $name;
        } else {
            throw new InvalidArgumentException('Component name must be string.');
        }

        if (!is_null($parent)) {
            $parent->addComponent($this, $name);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return IComponent
     * @throws Exception
     */
    public function getParent()
    {
        if (!$this->parent) {
            throw new Exception('Component has no parent.');
        }
        return $this->parent;
    }

    public function setSession(ISession $session)
    {
        $this->session = $session;
        $this->session->loadState();
    }

    /**
     * @return ISession|null
     */
    public function getSession()
    {
        return !$this->session && $this->parent ? $this->parent->getSession()->getEmptyClone($this->getFullName()) : $this->session;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFullName()
    {
        return $this->parent ? ($this->parent->getFullName() . '-' . $this->getName()) : $this->getName();
    }

    public function render()
    {
        $this->session->saveState();
    }

    protected function attached(IComponent $parent)
    {
        $this->parent = $parent;
    }

    public function isAttached()
    {
        return (bool)$this->parent;
    }

    protected function detached(IComponent $parent)
    {
        $this->parent = NULL;
    }

    public function addComponent(IComponent $component)
    {
        $name = $component->getName();
        if ($this->container->hasComponent($name)) {
            throw new InvalidArgumentException('Component with name ' . $name . ' is already exists.');
        }
        $component->attached($this);
        $this->container->attach($component);
    }

    public function removeComponent($name)
    {
        if (!$this->container->hasComponent($name)) {
            throw new InvalidArgumentException('Component with name ' . $name . ' does not exists.');
        }
        $component = $this->container->getComponent($name);
        $component->detached($this);
        $this->container->detach($component);
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        $this->addComponent($value, $offset);
    }

    public function offsetExists($offset)
    {
        return $this->container->hasComponent($offset);
    }

    public function offsetUnset($offset)
    {
        $this->removeComponent($offset);
    }

    /**
     * @param mixed $offset
     * @return IComponent
     * @throws InvalidArgumentException
     */
    public function offsetGet($offset)
    {
        if (!$this->container->hasComponent($offset)) {
            throw new InvalidArgumentException('Component with name ' . $offset . ' does not exists.');
        }
        return $this->container->getComponent($offset);
    }

    public function update(IContainer $container, IComponent $called_by)
    {
    }

    public function __toString()
    {
        $this->render();
        return '';
    }

    public function __clone()
    {
        throw new BadStateException('Can not clone component.');
    }

}
