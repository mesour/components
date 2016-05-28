<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\RandomString;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method Mesour\UI\Application getApplication($need = true)
 */
trait RandomString
{

	/**
	 * @return IRandomStringGenerator|CapturingRandomStringGenerator
	 */
	public function getRandomStringGenerator()
	{
		$context = $this->getApplication()->getContext();
		if (!$context->hasService(IRandomStringGenerator::class)) {
			$context->setService(new DefaultRandomStringGenerator(), IRandomStringGenerator::class);
		}
		return $context->getByType(IRandomStringGenerator::class);
	}

}
