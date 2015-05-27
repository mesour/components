<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ControlTest_Auth extends \Test\BaseTestCase
{

    public function testAuthSetAndGet()
    {
        $span = new \Tests\Span;

        $auth = new \Mesour\Components\Security\Auth;

        $span->setAuth($auth);

        Assert::same($span->getAuth(), $auth);
        Assert::true($span->getAuth()->isAllowed($span->link('http://mesour.com')));
    }

    public function testAuthFromParent()
    {
        $span = new \Tests\Span;

        $auth = new \Mesour\Components\Security\Auth;

        $span->setAuth($auth);

        $span->addComponent(new \Tests\Span, 'children');

        Assert::same($span->getComponent('children')->getAuth(), $auth);
    }

}

$test = new ControlTest_Auth();
$test->run();