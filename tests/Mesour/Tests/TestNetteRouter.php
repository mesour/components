<?php

namespace Mesour\Tests;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class TestNetteRouter
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

		$router[] = new Route('<module>/<presenter>/<action>[/<id>]/');

		return $router;
	}

}
