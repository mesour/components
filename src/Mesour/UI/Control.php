<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method Mesour\Components\Control\IControl getParent()
 * @method Mesour\Components\Control\BaseControl getComponent($name, $need = true)
 */
class Control extends Mesour\Components\Control\BaseControl implements Mesour\Components\Control\IControl
{

	public function createLink($handle, $args = [])
	{
		return $this->getApplication()->createLink($this, $handle, $args);
	}

}
