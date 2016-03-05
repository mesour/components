<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IAttributesControl extends IOptionsControl
{

	public function setAttributes(array $attributes);

	public function setAttribute($key, $value, $append = false, $translated = false);

	/**
	 * @param bool|FALSE $isDisabled
	 * @return array
	 * @throws Mesour\InvalidArgumentException
	 */
	public function getAttributes($isDisabled = false);

	public function getAttribute($key, $need = true);

	public function removeAttribute($key);

}
