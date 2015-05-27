<?php

use Tester\Assert;

$container = require_once __DIR__ . '/../bootstrap.php';

class Nette_SessionTest extends \Test\BaseNetteTestCase
{

    public function __construct(\Nette\DI\Container $container)
    {
        parent::__construct($container);
        $this->presenter = $this->getPresenter('TestNetteBridge:TestNette');
        $this->getSession()->destroy();
    }

    public function testSetAndGetSections()
    {
        $session = new \Mesour\Components\Bridges\Nette\Session($this->getSession());

        $test = $session->getSection('test');

        Assert::true($session->hasSection('test'));
        Assert::type('Mesour\Components\Session\ISessionSection', $test);

        $this->getSession()->close();
    }

    public function testSetAndGetValue()
    {
        $session = new \Mesour\Components\Bridges\Nette\Session($this->getSession());
        $test = $session->getSection('test');

        $testValue = 'test_text';

        $test->set('test', $testValue);

        Assert::same($test->get('test'), $testValue);
        Assert::same($test->get('unknown_key', 'default'), 'default');
    }

    public function testExceptions()
    {
        Assert::exception(function () {
            $session = new \Mesour\Components\Bridges\Nette\Session($this->getSession());
            $session->getSection(array());
        }, 'Mesour\Components\InvalidArgumentException');

        Assert::exception(function () {
            $session = new \Mesour\Components\Bridges\Nette\Session($this->getSession());
            $test = $session->getSection('test');

            $test->set(array(), TRUE);
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new Nette_SessionTest($container);
$test->run();