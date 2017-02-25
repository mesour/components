<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\RandomString;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IRandomString
{

	/**
	 * @return IRandomStringGenerator|CapturingRandomStringGenerator
	 */
	public function getRandomStringGenerator();

}
