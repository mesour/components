<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Localization;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface ITranslatable
{

	/**
	 * @param bool|FALSE $fromChildren
	 * @return ITranslator
	 */
	public function getTranslator($fromChildren = false);

	/**
	 * @param ITranslator $translator
	 * @return self
	 */
	public function setTranslator(ITranslator $translator);

	/**
	 * @param bool $disabled
	 * @return $this
	 */
	public function setDisableTranslate($disabled = true);

	public function isDisabledTranslate();

}
