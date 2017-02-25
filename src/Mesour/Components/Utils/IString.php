<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Utils;

use Nette\Utils\IHtmlString;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IString extends IHtmlString
{

	public function __toString();

}
