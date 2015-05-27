<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ControlTest_Session extends \Test\BaseTestCase
{

    public function testSessionSetAndGet()
    {
        $span = new \Tests\Span;

        $session = new \Mesour\Components\Session\Session;

        $span->setSession($session);

        Assert::same($span->getSession(), $session);
    }

    public function testSessionFromParent()
    {
        $span = new \Tests\Span;

        $session = new \Mesour\Components\Session\Session;

        $span->setSession($session);

        $span->addComponent(new \Tests\Span, 'children');

        Assert::same($span->getComponent('children')->getSession(), $session);
    }

}

$test = new ControlTest_Session();
$test->run();