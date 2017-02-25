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
class ConstantDateTimeProvider implements IDateTimeProvider
{

	/**
	 * @var \DateTime
	 */
	private $currentDateTime;

	public function __construct(\DateTime $dateTime)
	{
		$this->currentDateTime = $dateTime;
	}

	public function getDate()
	{
		return new \DateTime($this->currentDateTime->format('Y-m-d'), clone $this->currentDateTime->getTimezone());
	}

	public function getDateTime()
	{
		return clone $this->currentDateTime;
	}

	/**
	 * @return \DateTimeZone
	 */
	public function getTimeZone()
	{
		return clone $this->currentDateTime->getTimezone();
	}

	/**
	 * @param \DateTimeZone $timezone
	 */
	public function setDefaultTimeZone(\DateTimeZone $timezone)
	{
		$this->currentDateTime->setTimezone($timezone);
	}

}
