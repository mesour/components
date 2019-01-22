<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\ComponentModel;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
abstract class Component implements IComponent
{

	use Mesour\SmartObject;

	private $name;

	/**
	 * @var Component
	 */
	private $parent;

	/**
	 * @param string|null $name
	 * @param IContainer $parent
	 * @throws Mesour\InvalidArgumentException
	 */
	public function __construct($name = null, IContainer $parent = null)
	{
		if (is_null($name) || Mesour\Components\Utils\Helpers::validateComponentName($name, false)) {
			$this->name = $name;
		} else {
			throw new Mesour\InvalidArgumentException('Component name must be integer, string or null.');
		}

		if (!is_null($parent)) {
			$parent->addComponent($this, $name);
		}
	}

	/**
	 * @return Component|null
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		Mesour\Components\Utils\Helpers::validateComponentName($name);
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getName()
	{
		return $this->name;
	}

	public function getFullName()
	{
		return $this->parent ? ($this->parent->getFullName() . '-' . $this->getName()) : $this->getName();
	}

	public function render()
	{
	}

	/**
	 * @param IContainer $parent
	 */
	public function attached(IContainer $parent)
	{
		$this->parent = $parent;
		if ($this instanceof IContainer) {
			foreach ($this->getComponents() as $component) {
				$component->attached($this);
			}
		}
	}

	/**
	 * @param IContainer $parent
	 */
	public function detached(IContainer $parent)
	{
		$this->parent = null;
	}

	public function __clone()
	{
		if ($this->parent === null) {
			return;
		} elseif ($this->parent instanceof Container && $this->parent->_isCloning()) {
			$this->attached($this->parent->_isCloning());
		} else {
			$this->parent = null;
		}
	}

	public function __wakeup()
	{
		throw new Mesour\NotSupportedException(
			sprintf('Object unserialization is not supported by class ', get_class($this))
		);
	}

	public function __sleep()
	{
		throw new Mesour\NotSupportedException(
			sprintf('Object serialization is not supported by class ', get_class($this))
		);
	}

}
