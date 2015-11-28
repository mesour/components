<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Container extends Component implements IContainer
{

    /**
     * @var IComponent[]
     */
    private $components = [];

    /** @var IComponent|NULL */
    private $cloning;

    /**
     * @param IComponent $component
     * @param string|null $name
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addComponent(IComponent $component, $name = NULL, $x = false)
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
     * @param $className
     * @param bool $need
     * @param bool $reverse
     * @return IComponent|null
     * @throws InvalidArgumentException
     */
    public function lookup($className, $need = TRUE, $reverse = FALSE)
    {
        if (!$reverse) {
            foreach ($this->components as $component) {
                /** @var IContainer $component */
                if (get_class($component) === $className || is_subclass_of($component, $className)) {
                    return $component;
                }
                $out = $component->lookup($className, $need, $reverse);
                if ($out) {
                    return $out;
                }
            }
        } else {
            $parent = $this->getParent();
            if ($parent instanceof IContainer) {
                if (get_class($parent) === $className || is_subclass_of($parent, $className)) {
                    return $parent;
                } else {
                    return $parent->lookup($className, $need, $reverse);
                }
            }
        }
        if ($need) {
            throw new InvalidArgumentException('Can not find component with class name ' . $className . '.');
        } else {
            return NULL;
        }
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
