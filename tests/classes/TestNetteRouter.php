<?php

namespace Test;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class TestNetteRouter
{

    /** @return \Nette\Application\IRouter */
    static public function createRouter()
    {
        $router = new RouteList;

        $router[] = new Route("<module>/<presenter>/<action>[/<id>]/");

        return $router;
    }

}