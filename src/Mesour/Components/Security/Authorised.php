<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Security;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method Mesour\UI\Application getApplication($need = true)
 */
trait Authorised
{

	private $permission = [];

	/**
	 * @return IAuthorizator|Permission|object
	 */
	public function getAuthorizator()
	{
		$context = $this->getApplication()->getContext();
		if (!$context->hasService(IAuthorizator::class)) {
			$context->setService(new Permission(), IAuthorizator::class);
		}
		return $context->getByType(IAuthorizator::class);
	}

	protected function setPermissionCheck($resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL)
	{
		$this->permission = [$resource, $privilege];
	}

	protected function getUserRole()
	{
		return $this->getApplication()->getUser()->getRoles();
	}

	public function isAllowed()
	{
		if (!$this->permission) {
			return true;
		}
		foreach ($this->getApplication()->getUser()->getRoles() as $role) {
			$args = array_merge([$role], $this->permission);
			if (Mesour\Components\Utils\Helpers::invokeArgs([$this->getAuthorizator(), 'isAllowed'], $args)) {
				return true;
			}
		}

		return false;
	}

}
