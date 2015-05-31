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
interface IComponent
{

    /**
     * @param string|null $name
     * @param IContainer $parent
     */
    public function __construct($name = NULL, IContainer $parent = NULL);

    /**
     * @param IContainer $parent
     */
    public function attached(IContainer $parent);

    /**
     * @param IContainer $parent
     */
    public function detached(IContainer $parent);

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @return Component|null
     */
    public function getParent();

    public function render();

}
