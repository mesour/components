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
abstract class Statement
{

    protected $states = array();

    public function __construct(array $default_state = array())
    {
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            if ($property->isPrivate()) {
                throw new BadStateException('State variable must be public or protected.');
            }

            $haystack = $property->getDocComment();
            $needle = '@state';
            if (strpos($haystack, $needle) !== FALSE) {
                $this->states[] = $name = $property->getName();
                if (isset($default_state[$name])) {
                    $this->{'set' . ucfirst($name)}((bool)$default_state[$name]);
                }
            }
        }
    }

    public function __call($name, array $parameters = array())
    {
        if (substr($name, 0, 2) === 'is') {
            return $this->{lcfirst(substr($name, 2))};
        }
        if (substr($name, 0, 3) === 'set') {
            if (count($parameters) === 0) {
                throw new InvalidArgumentException('Missing first parameter value.');
            }
            $this->{lcfirst(substr($name, 3))} = (bool)$parameters[0];
            return $this;
        }
        throw new Exception('Call undefined method on ' . get_class($this) . '.');
    }

}
