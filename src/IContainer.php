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
interface IContainer extends \Iterator, \ArrayAccess, \Countable
{

    /**
     * @param IComponent $component
     * @param string|null $name
     * @return mixed
     */
    public function addComponent(IComponent $component, $name = NULL);

    /**
     * @param $name
     * @return mixed
     */
    public function removeComponent($name);

    /**
     * @param $name
     * @param bool $need
     * @return IComponent|null
     */
    public function getComponent($name, $need = TRUE);

    /**
     * @return IComponent[]
     */
    public function getComponents();

}
