<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

use Mesour\Components\Session\ISession;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface IComponent extends \ArrayAccess
{

    public function __construct($name, IComponent $component = NULL);

    public function setSession(ISession $session);

    /**
     * @return ISession
     */
    public function getSession();

    public function getName();

    /**
     * @return IContainer
     */
    public function getContainer();

    public function isAttached();

    public function addComponent(IComponent $component);

    public function removeComponent($name);

    public function render();

}
