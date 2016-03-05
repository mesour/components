<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\ComponentModel;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IContainer extends \Iterator, \ArrayAccess, \Countable
{

	/**
	 * @param IComponent $component
	 * @param string|null $name
	 * @return mixed
	 */
	public function addComponent(IComponent $component, $name = null);

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
	public function getComponent($name, $need = true);

	/**
	 * @return IComponent[]
	 */
	public function getComponents();

	/**
	 * @param $className
	 * @param bool $need
	 * @param bool $reverse
	 * @return IComponent|null
	 */
	public function lookup($className, $need = true, $reverse = false);

}
