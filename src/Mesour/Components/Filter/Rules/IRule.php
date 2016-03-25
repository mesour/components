<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Filter\Rules;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IRule
{

	public function getName();

	public function isMatch(Mesour\Components\ComponentModel\IComponent $component, $value, array $parameters = []);

}
