<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
abstract class Object
{

	/**
	 * @var \ReflectionClass
	 */
	private $reflection;

	/**
	 * @return \ReflectionClass
	 */
	public function getReflection()
	{
		if (!$this->reflection) {
			$this->reflection = new \ReflectionClass($this);
		}
		return $this->reflection;
	}

	public function __get($name)
	{
		throw new Mesour\MemberAccessException("Trying to get undefined property $name.");
	}

	public function __set($name, $value)
	{
		throw new Mesour\MemberAccessException(sprintf("Cannot write to an undeclared property %s::\$$name", static::class));
	}

	public function __unset($name)
	{
		throw new Mesour\MemberAccessException(sprintf("Cannot unset the property %s::\$$name.", static::class));
	}

	public function __call($name, $args)
	{
		try {
			if (substr($name, 0, 2) === 'on') {
				if (!$this->getReflection()->hasProperty($name)) {
					throw new Mesour\Components\MethodCallException;
				} elseif ($this->getReflection()->getProperty($name)->isPrivate()) {
					throw new Mesour\InvalidStateException('Property ' . $name . ' must be public or protected.');
				} elseif (!is_array($this->{$name})) {
					throw new Mesour\UnexpectedValueException('Property ' . $name . ' must be array.');
				} else {
					foreach ($this->{$name} as $callback) {
						Mesour\Components\Utils\Helpers::invokeArgs($callback, $args);
					}
				}
			} else {
				throw new Mesour\Components\MethodCallException;
			}
		} catch (Mesour\Components\MethodCallException $e) {
			throw new Mesour\MethodCallException(sprintf("Cannot call undefined method %s::\$$name.", static::class));
		}
	}

}
