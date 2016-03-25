<?php
/**
 * Mesour Components
 *
 * @license       LGPL-3.0 and BSD-3-Clause
 * @Copyright (c) 2015-2016 Matous Nemec <http://mesour.com>
 */

namespace Mesour\ComponentsTests\Classes;

use Mesour;

/**
 * @author  mesour <http://mesour.com>
 * @package Mesour Components
 */
class TestTranslator implements Mesour\Components\Localization\ITranslator
{

	private $translates = [
		'translated' => 'new_string',
	];

	public function translate($message, $count = null)
	{
		if (isset($this->translates[$message])) {
			return $this->translates[$message];
		}
		return $message;
	}

}
