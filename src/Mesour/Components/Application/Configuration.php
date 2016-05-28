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
class Configuration extends Mesour\Object
{

	protected $parameters = [];

	protected $services = [];

	public function setTempDir($tempDir)
	{
		$this->setParameter('tempDir', $tempDir);
	}

	public function getTempDir()
	{
		return $this->getParameter('tempDir', sys_get_temp_dir() . PHP_EOL);
	}

	private function setParameter($key, $value)
	{
		$this->parameters[$key] = $value;
	}

	private function getParameter($key, $default = null)
	{
		return isset($this->parameters[$key]) ? $this->parameters[$key] : $default;
	}

}
