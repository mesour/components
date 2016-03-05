<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Link;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface ILink
{

	/**
	 * @param $destination
	 * @param array $args
	 * @return Url
	 */
	public function create($destination, $args = []);

}
