<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ControlTest_Link extends \Test\BaseTestCase
{

    public function testDefaultLink()
    {
        $span = new \Tests\Span;

        $link = new \Mesour\Components\Link\Link;

        $span->setLink($link);

        Assert::same($span->getLink(), $link);
    }

    public function testLinkMethod()
    {
        $span = new \Tests\Span;

        $link = new \Mesour\Components\Link\Link;
        $span->setLink($link);

        $address = 'http://mesour.com';
        $args = array('key' => 'val[]');
        $complete_address = 'http://mesour.com?key=val%5B%5D';

        Assert::same($span->getLink(), $link);

        $url = $span->link($address, $args);

        Assert::type('Mesour\Components\Link\IUrl', $url);
        Assert::same($url->create(), $complete_address);
    }

    public function testLinkSetAndGet()
    {
        $span = new \Tests\Span;

        $link = new \Mesour\Components\Link\Link;

        $span->setLink($link);

        Assert::same($span->getLink(), $link);
    }

    public function testLinkFromParent()
    {
        $span = new \Tests\Span;

        $link = new \Mesour\Components\Link\Link;

        $span->setLink($link);

        $span->addComponent(new \Tests\Span, 'children');

        Assert::same($span->getComponent('children')->getLink(), $link);
    }

}

$test = new ControlTest_Link();
$test->run();