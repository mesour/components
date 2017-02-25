<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\DateTimeProvider;

use Mesour;

/**
 * @author Matouš Němec (http://mesour.com)
 */
class DefaultDateTimeProvider implements IDateTimeProvider
{

	/**
	 * @var \DateTimeZone
	 */
	private $timezone;

	/**
	 * @return \DateTime
	 */
	public function getDate()
	{
		return new \DateTime(date('Y-m-d'), $this->getTimeZone());
	}

	/**
	 * @return \DateTime
	 */
	public function getDateTime()
	{
		return new \DateTime('now', $this->getTimeZone());
	}

	/**
	 * @return \DateTimeZone
	 */
	public function getTimeZone()
	{
		if (!$this->timezone) {
			$this->timezone = new \DateTimeZone(date_default_timezone_get());
		}
		return $this->timezone;
	}

	/**
	 * @param \DateTimeZone $timezone
	 */
	public function setDefaultTimeZone(\DateTimeZone $timezone)
	{
		$this->timezone = $timezone;
	}

}
