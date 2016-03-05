<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Session;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface ISessionSection
{

	public function remove();

	public function set($key, $val);

	public function get($key = null, $default = null);

	public function loadState($data);

}
