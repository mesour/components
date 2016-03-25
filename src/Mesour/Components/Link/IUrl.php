<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Link;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IUrl extends Mesour\Components\Utils\IString
{

	/**
	 * @param ILink $link
	 * @param string $destination
	 * @param array $args
	 */
	public function __construct(ILink $link, $destination, $args = []);

	/**
	 * @param array $data
	 * @return string
	 */
	public function create($data = []);

	/**
	 * @return ILink
	 */
	public function getLink();

	/**
	 * @return string
	 */
	public function getDestination();

	/**
	 * @return array
	 */
	public function getArguments();

}
