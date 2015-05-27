<?php

use Tester\Assert;

$container = require_once __DIR__ . '/../bootstrap.php';

class Nette_LinkTest extends \Test\BaseNetteTestCase
{

    public function testCreate()
    {
        $name = 'TestNetteBridge:TestNette';

        /** @var \TestNetteBridgeModule\TestNettePresenter $presenter */
        $presenter = $this->getPresenter($name);
        $presenter->_saveGlobalState();

        $link = new \Mesour\Components\Bridges\Nette\Link($presenter);

        $created = $link->create($name . ':');

        Assert::type('Mesour\Components\Link\IUrl', $created);
        Assert::same($created->create(), '/test-nette-bridge/test-nette/default/');
    }

}

$test = new Nette_LinkTest($container);
$test->run();