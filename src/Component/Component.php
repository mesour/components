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
abstract class Component extends Events implements IComponent
{

    private $name;

    /**
     * @var Component
     */
    private $parent;

    /**
     * @param string|null $name
     * @param IContainer $parent
     * @throws InvalidArgumentException
     */
    public function __construct($name = NULL, IContainer $parent = NULL)
    {
        if (is_null($name) || Helper::validateComponentName($name, FALSE)) {
            $this->name = $name;
        } else {
            throw new InvalidArgumentException('Component name must be integer, string or null.');
        }

        if (!is_null($parent)) {
            $parent->addComponent($this, $name);
        }
    }

    /**
     * @return Component|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        Helper::validateComponentName($name);
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
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

    /**
     * @param IContainer $parent
     */
    public function attached(IContainer $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param IContainer $parent
     */
    public function detached(IContainer $parent)
    {
        $this->parent = NULL;
    }

    public function __clone()
    {
        if ($this->parent === NULL) {
            return;
        } elseif ($this->parent instanceof Container) {
            $this->parent = $this->parent->_isCloning();
        } else {
            $this->parent = NULL;
        }
    }

    public function __wakeup()
    {
        throw new Exception('Object unserialization is not supported by class ' . get_class($this));
    }

    public function __sleep()
    {
        throw new Exception('Object unserialization is not supported by class ' . get_class($this));
    }

}
