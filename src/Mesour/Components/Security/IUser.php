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
interface IUser
{

	/**
	 * @param string|string[] $roles
	 */
	public function setRoles($roles);

	/**
	 * @return string[]
	 */
	public function getRoles();

	/**
	 * @return bool
	 */
	public function isLoggedIn();

	/**
	 * @param bool $loggedIn
	 */
	public function setLoggedIn($loggedIn);

}
