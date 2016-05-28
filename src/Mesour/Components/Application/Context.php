<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Application;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class Context extends Mesour\Object
{

	/**
	 * @var object[]
	 */
	private $services = [];

	private $freezed = [];

	public function setService($service, $type = null)
	{
		if (!is_object($service)) {
			throw new Mesour\InvalidArgumentException(sprintf('Service must be object. %s given.', gettype($service)));
		}
		if (!$type) {
			$type = $this->determineServiceType($service);
		} else {
			if (!$service instanceof $type) {
				throw new Mesour\InvalidArgumentException(
					sprintf('Service %s must be instance of %s if set service type.', get_class($service), $type)
				);
			}
		}
		if ($this->hasService($type) && $this->isFreezed($type)) {
			throw new Mesour\InvalidStateException('Can not set service after is some service used.');
		}
		$this->services[$type] = $service;

		return $this;
	}

	public function getByType($type)
	{
		$this->freeze($type);
		if (!$this->hasService($type)) {
			throw new Mesour\InvalidArgumentException(sprintf('Service of type %s does not exist.', $type));
		}
		return $this->services[$type];
	}

	public function hasService($type)
	{
		return isset($this->services[$type]);
	}

	public function isFreezed($type)
	{
		return isset($this->freezed[$type]);
	}

	private function determineServiceType($service)
	{
		return get_class($service);
	}

	private function freeze($type)
	{
		$this->freezed[$type] = true;
	}

}
