<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Application;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IPayload
{

	/**
	 * @param string $key
	 * @param $value
	 * @return mixed
	 */
	public function set($key, $value);

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key = null, $default = null);

	public function sendPayload();

}
