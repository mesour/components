<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Security;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class User implements IUser
{

	private $loggedIn = false;

	/**
	 * @var string[] $roles
	 */
	private $roles = ['guest'];

	/**
	 * @param string|string[] $roles
	 */
	public function setRoles($roles)
	{
		if (!is_array($roles)) {
			$this->roles = [$roles];
		} else {
			$this->roles = $roles;
		}
	}

	/**
	 * @return string[]
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return $this->loggedIn;
	}

	/**
	 * @param bool $loggedIn
	 */
	public function setLoggedIn($loggedIn)
	{
		$this->loggedIn = (bool) $loggedIn;
	}

}
