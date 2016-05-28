<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\DateTimeProvider;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method Mesour\UI\Application getApplication($need = true)
 */
trait HasDateTimeProvider
{

	/**
	 * @return IDateTimeProvider
	 */
	public function getDateTimeProvider()
	{
		$context = $this->getApplication()->getContext();
		if (!$context->hasService(IDateTimeProvider::class)) {
			$context->setService(new DefaultDateTimeProvider, IDateTimeProvider::class);
		}
		return $context->getByType(IDateTimeProvider::class);
	}

}
