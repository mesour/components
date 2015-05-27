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
class Container extends Component implements IContainer
{

    /**
     * @var IComponent[]
     */
    private $components = array();

    /** @var IComponent|NULL */
    private $cloning;

    /**
     * @param IComponent $component
     * @param string|null $name
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addComponent(IComponent $component, $name = NULL)
    {
        /** @var IComponent $component */
        $name = is_null($name) ? $component->getName() : $name;
        Helper::validateComponentName($name);
        if (isset($this->components[$name])) {
            throw new InvalidArgumentException('Component with name ' . $name . ' is already exists.');
        }
        $component->setName($name);
        $this->components[$name] = $component;
        $component->attached($this);
        return $this;
    }

    /**
     * @param $name
     * @return $this
     * @throws InvalidArgumentException
     */
    public function removeComponent($name)
    {
        $component = $this->getComponent($name);
        $component->detached($this);
        unset($this->components[$name]);
        return $this;
    }

    /**
     * @return IComponent[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @param $name
     * @param bool $need
     * @return IComponent|null
     * @throws InvalidArgumentException
     */
    public function getComponent($name, $need = TRUE)
    {
        Helper::validateComponentName($name);
        if (!isset($this->components[$name])) {
            if ($need) {
                throw new InvalidArgumentException('Component with name ' . $name . ' does not exists.');
            }
            return NULL;
        }
        return $this->components[$name];
    }

    /**
     * @return IComponent
     */
    public function rewind()
    {
        reset($this->components);
    }

    /**
     * @return IComponent
     */
    public function current()
    {
        $var = current($this->components);
        return $var;
    }

    /**
     * @return int
     */
    public function key()
    {
        return key($this->components);
    }

    /**
     * @return IComponent
     */
    public function next()
    {
        $var = next($this->components);
        return $var;
    }

    public function valid()
    {
        $key = key($this->components);
        return ($key !== NULL && $key !== FALSE);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->components);
    }

    public function offsetSet($offset, $value)
    {
        if ($value instanceof IComponent) {
            $this->addComponent($value, $offset);
        } else {
            throw new InvalidArgumentException('Component must be instance of IComponent.');
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->components[$offset]);
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
        return $this->getComponent($offset);
    }

    public function __clone()
    {
        if ($this->components) {
            $oldMyself = reset($this->components)->getParent();
            $oldMyself->cloning = $this;
            foreach ($this->components as $name => $component) {
                $this->components[$name] = clone $component;
            }
            $oldMyself->cloning = NULL;
        }
        parent::__clone();
    }

    public function _isCloning()
    {
        return $this->cloning;
    }

}
