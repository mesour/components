<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Session;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Session implements ISession
{

	/**
	 * @var array
	 */
	private $sections = [];

	/**
	 * @var array
	 */
	private $session = [];

	private $loaded = false;

	public function __construct()
	{
		self::sessionStart();
		$this->loadState();
	}

	/**
	 * @param $section
	 * @return ISessionSection
	 * @throws Mesour\InvalidArgumentException
	 */
	public function getSection($section)
	{
		if (!$this->hasSection($section)) {
			$this->sections[$section] = $session_section = new SessionSection($section);
			$session_section->loadState(isset($this->session[$section]) ? $this->session[$section] : []);
		}
		return $this->sections[$section];
	}

	public function hasSection($section)
	{
		if (!Mesour\Components\Utils\Helpers::validateKeyName($section)) {
			throw new Mesour\InvalidArgumentException(
				sprintf('SessionSection name must be integer or string, %s given.', gettype($section))
			);
		}
		return isset($this->sections[$section]);
	}

	public function destroy()
	{
		unset($_SESSION[__NAMESPACE__]);
		return $this;
	}

	public function loadState()
	{
		if (!$this->loaded) {
			$this->loaded = true;
			$this->session = $this->getFromSession();
		}
		return $this;
	}

	private function getFromSession($default = [])
	{
		return isset($_SESSION[__NAMESPACE__]) ? $_SESSION[__NAMESPACE__] : $default;
	}

	public function saveState()
	{
		$this->session = $this->getFromSession();
		foreach ($this->sections as $name => $section) {
			/** @var ISessionSection $section */
			$data = $section->get();
			$this->session[$name] = $data;
		}
		$_SESSION[__NAMESPACE__] = $this->session;
		return $this;
	}

	static private function sessionStart()
	{
		if (session_id() == '') {
			session_start();
		}
	}

}
