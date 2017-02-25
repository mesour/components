<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Localization;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface ITranslator
{

	/**
	 * Translates the given string.
	 * @param string $message
	 * @param int $count
	 * @return string
	 */
	public function translate($message, $count = null);

}
