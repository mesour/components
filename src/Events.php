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
abstract class Events
{

    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * @return \ReflectionClass
     */
    public function getReflection() {
        if(!$this->reflection) {
            $this->reflection = new \ReflectionClass($this);
        }
        return $this->reflection;
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 2) === 'on') {
            if (!$this->getReflection()->hasProperty($name)) {
                throw new InvalidArgumentException('Property ' . $name . ' is not defined.');
            } elseif (!is_array($this->{$name})) {
                throw new InvalidArgumentException('Property ' . $name . ' must be array.');
            } else {
                foreach ($this->{$name} as $callback) {
                    Helper::invokeArgs($callback, $args);
                }
            }
        } else {
            throw new Exception('Call undefined method "' . $name . '" on ' . get_class($this) . '.');
        }
    }

}
