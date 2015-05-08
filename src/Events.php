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

    public function __call($name, $args)
    {
        if(substr($name, 0, 2) === 'on') {
            if(!isset($this->{$name})) {
                throw new InvalidArgumentException('Property ' . $name . ' is not defined.');
            } elseif(!is_array($this->{$name})) {
                throw new InvalidArgumentException('Property ' . $name . ' must be array.');
            } else {
                foreach($this->{$name} as $callback) {
                    if(!is_callable($callback)) {
                        throw new InvalidArgumentException('Callback for event ' . $name . ' is not callable.');
                    }
                    call_user_func_array($callback, $args);
                }
            }
        } else {
            throw new Exception('Call undefined method "' . $name . '" on ' . get_class($this) . '.');
        }
    }

}
