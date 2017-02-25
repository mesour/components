<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\ComponentModel;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IComponent
{

	/**
	 * @param string|null $name
	 * @param IContainer $parent
	 */
	public function __construct($name = null, IContainer $parent = null);

	/**
	 * @param IContainer $parent
	 */
	public function attached(IContainer $parent);

	/**
	 * @param IContainer $parent
	 */
	public function detached(IContainer $parent);

	/**
	 * @param string $name
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
