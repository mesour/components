<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\ComponentModel;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class Container extends Component implements IContainer
{

	/**
	 * @var IComponent[]
	 */
	private $components = [];

	/** @var IComponent|NULL */
	private $cloning;

	/**
	 * @param IComponent $component
	 * @param string|null $name
	 * @return $this
	 * @throws Mesour\InvalidStateException
	 */
	public function addComponent(IComponent $component, $name = null)
	{
		/** @var IComponent $component */
		$name = is_null($name) ? $component->getName() : $name;
		Mesour\Components\Utils\Helpers::validateComponentName($name);
		if (isset($this->components[$name])) {
			throw new Mesour\InvalidStateException(
				sprintf('Component with name %s is already exists.', $name)
			);
		}
		$component->setName($name);
		$this->components[$name] = $component;
		$component->attached($this);
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function removeComponent($name)
	{
		$component = $this->getComponent($name);
		$component->detached($this);
		unset($this->components[$name]);
		return $this;
	}

	/**
	 * @return IComponent[]
	 */
	public function getComponents()
	{
		return $this->components;
	}

	/**
	 * @param string $name
	 * @param bool $need
	 * @return IComponent|null
	 * @throws Mesour\InvalidStateException
	 */
	public function getComponent($name, $need = true)
	{
		Mesour\Components\Utils\Helpers::validateComponentName($name);
		if (!isset($this->components[$name])) {
			if ($need) {
				throw new Mesour\InvalidStateException(
					sprintf('Component with name %s does not exists.', $name)
				);
			}
			return null;
		}
		return $this->components[$name];
	}

	/**
	 * @param string $className
	 * @param bool $need
	 * @param bool $reverse
	 * @return IComponent|null
	 * @throws Mesour\Components\NotFoundException
	 */
	public function lookup($className, $need = true, $reverse = false)
	{
		if (!$reverse) {
			foreach ($this->components as $component) {
				/** @var IContainer $component */
				if (get_class($component) === $className || is_subclass_of($component, $className)) {
					return $component;
				}
				$out = $component->lookup($className, $need, $reverse);
				if ($out) {
					return $out;
				}
			}
		} else {
			$parent = $this->getParent();
			if ($parent instanceof IContainer) {
				if (get_class($parent) === $className || is_subclass_of($parent, $className)) {
					return $parent;
				} else {
					return $parent->lookup($className, $need, $reverse);
				}
			}
		}
		if ($need) {
			throw new Mesour\Components\NotFoundException(
				sprintf('Cannot find component with class name %s.', $className)
			);
		} else {
			return null;
		}
	}

	/**
	 * @param Mesour\Components\Filter\Rules\RulesContainer|NULL $rulesContainer
	 * @return Mesour\Components\Filter\FilterIterator
	 * @internal
	 */
	public function createFilterIterator(Mesour\Components\Filter\Rules\RulesContainer $rulesContainer = null)
	{
		if (!$rulesContainer) {
			$rulesContainer = new Mesour\Components\ComponentModel\Filter\BaseRules;
		}
		$filter = new Mesour\Components\Filter\FilterIterator($this);
		$filter->setRulesContainer($rulesContainer);
		return $filter;
	}

	public function rewind()
	{
		reset($this->components);
	}

	/**
	 * @return IComponent
	 */
	public function current()
	{
		$var = current($this->components);
		return $var;
	}

	/**
	 * @return int
	 */
	public function key()
	{
		return key($this->components);
	}

	/**
	 * @return IComponent
	 */
	public function next()
	{
		$var = next($this->components);
		return $var;
	}

	public function valid()
	{
		$key = key($this->components);
		return ($key !== null && $key !== false);
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->components);
	}

	public function offsetSet($offset, $value)
	{
		if ($value instanceof IComponent) {
			$this->addComponent($value, $offset);
		} else {
			throw new Mesour\InvalidStateException(
				sprintf('Component must be instance of %s.', IComponent::class)
			);
		}
	}

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->components[$offset]);
	}

	public function offsetUnset($offset)
	{
		$this->removeComponent($offset);
	}

	/**
	 * @param mixed $offset
	 * @return IComponent
	 * @throws Mesour\InvalidStateException
	 */
	public function offsetGet($offset)
	{
		return $this->getComponent($offset);
	}

	public function __clone()
	{
		if ($this->components) {
			$oldMyself = reset($this->components)->getParent();
			$oldMyself->cloning = $this;
			foreach ($this->components as $name => $component) {
				$this->components[$name] = clone $component;
			}
			$oldMyself->cloning = null;
		}
		parent::__clone();
	}

	public function _isCloning()
	{
		return $this->cloning;
	}

}
