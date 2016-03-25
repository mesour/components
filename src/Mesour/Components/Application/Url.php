<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Application;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class Url
{

	protected $destination;

	protected $args = [];

	protected $users_args = [];

	public function __construct($requestUri)
	{
		$requestUri = urldecode($requestUri);
		if (strpos($requestUri, '?') !== false) {
			$explode = explode('?', $requestUri);
			$this->destination = $explode[0];
			parse_str($explode[1], $this->args);
			foreach ($this->args as $key => $arg) {
				if (substr($key, 0, 2) !== 'm_') {
					$this->users_args[$key] = $arg;
				}
			}
		} else {
			$this->destination = $requestUri;
		}
	}

	public function create(Mesour\UI\Control $control, $handle, $args = [])
	{
		if (!is_string($handle)) {
			throw new Mesour\InvalidArgumentException(
				sprintf('Second parameter handle must be string. %s given.', gettype($handle))
			);
		}
		$linkName = $control->createLinkName();

		$newArgs = [];
		foreach ($args as $key => $value) {
			$newArgs['m_' . $linkName . '-' . $key] = $value;
		}

		$args = array_merge_recursive($newArgs, $this->users_args);

		$args['m_do'] = $linkName . '-' . $handle;
		return $this->createUrl($args);
	}

	protected function createUrl($args = [])
	{
		$query = http_build_query($args);
		return $this->destination . (
		count($args) > 0
			? (strpos($this->destination, '?') !== false ? '&' : '?')
			: ''
		) . $query;
	}

	/**
	 * @return string
	 */
	public function getDestination()
	{
		return $this->destination;
	}

	/**
	 * @return array
	 */
	public function getArguments()
	{
		return $this->args;
	}

}
