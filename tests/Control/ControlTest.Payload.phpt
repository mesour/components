<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ControlTest_Payload extends \Test\BaseTestCase
{

    public function testPayloadSetAndGet()
    {
        $span = new \Tests\Span;

        $payload = new \Mesour\Components\Application\Payload;

        $span->setPayload($payload);

        Assert::same($span->getPayload(), $payload);
    }

    public function testPayloadFromParent()
    {
        $span = new \Tests\Span;

        $payload = new \Mesour\Components\Application\Payload;

        $span->setPayload($payload);

        $span->addComponent(new \Tests\Span, 'children');

        Assert::same($span->getComponent('children')->getPayload(), $payload);
    }

}

$test = new ControlTest_Payload();
$test->run();