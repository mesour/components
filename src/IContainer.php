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
interface IContainer
{

    public function hasComponent($name);

    public function attach(IComponent $component);

    public function detach(IComponent $component);

    public function notify(IComponent $called_by = NULL);

}
