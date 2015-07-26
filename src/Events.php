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
abstract class Events
{

    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * @return \ReflectionClass
     */
    public function getReflection()
    {
        if (!$this->reflection) {
            $this->reflection = new \ReflectionClass($this);
        }
        return $this->reflection;
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 2) === 'on') {
            if (!$this->getReflection()->hasProperty($name)) {
                throw new InvalidArgumentException('Property ' . $name . ' is not defined.');
            } elseif ($this->getReflection()->getProperty($name)->isPrivate()) {
                throw new InvalidArgumentException('Property ' . $name . ' must not be private.');
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
