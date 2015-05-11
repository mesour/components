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
class Container implements IContainer
{

    /**
     * @var array
     */
    protected $components = array();

    private $position = 0;

    public function attach(IComponent $component)
    {
        return $this->components[$component->getName()] = $component;
    }

    public function detach(IComponent $component)
    {
        foreach ($this->components as $key => $_component) {
            if ($_component === $component) {
                unset($this->components[$key]);
            }
        }
    }

    public function notify(IComponent $called_by = NULL)
    {
        foreach ($this->components as $component) {
            /** @var Component $component */
            $component->update($this, $called_by);
        }
    }

    public function hasComponent($name)
    {
        return isset($this->components[$name]);
    }

    /**
     * @param $name
     * @return IComponent
     */
    public function getComponent($name)
    {
        return $this->components[$name];
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->components[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->components[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->components);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->components[$offset]);
    }

    /**
     * @param mixed $offset
     * @return IContainer
     */
    public function offsetGet($offset)
    {
        return $this->components[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception('You cant set route.');
    }

    public function offsetUnset($offset)
    {
        throw new Exception('You cant unset route.');
    }

}
