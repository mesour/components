<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
abstract class Component extends Events implements IComponent, IObserver
{

    private $name;

    /**
     * @var Component
     */
    private $parent;

    /**
     * @var IContainer
     */
    private $container;

    public function __construct($name = NULL, IComponent $parent = NULL)
    {
        $this->container = new Container();

        if (is_string($name) || is_null($name)) {
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
     * @return Component|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
        /** @var self $component */
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
        /** @var self $component */
        $component = $this->container->getComponent($name);
        $component->detached($this);
        $this->container->detach($component);
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        if($value instanceof IComponent) {
            $value->setName($offset);
            $this->addComponent($value, $offset);
        } else {
            throw new InvalidArgumentException('Component must be instance of IComponent.');
        }
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
