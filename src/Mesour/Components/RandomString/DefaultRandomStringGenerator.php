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
use Nette\Utils\Random;

/**
 * @author Matouš Němec (http://mesour.com)
 */
class DefaultRandomStringGenerator implements IRandomStringGenerator
{

	public function generate()
	{
		return Random::generate(10);
	}

}
