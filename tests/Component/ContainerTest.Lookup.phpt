<?php

use Tester\Assert;
use Tests\TestComponent;

require_once __DIR__ . '/../bootstrap.php';

class ContainerTest extends \Test\BaseTestCase
{

    public function testOnComponent()
    {
        $master = new TestComponent();
        $span = new \Tests\Span('test_name', $master);

        Assert::same($span, $master->lookup('Tests\Span'));
    }

    public function testNestedInChildren()
    {
        $master = new TestComponent();
        $children = new TestComponent('children', $master);
        $children2 = new TestComponent('children', $children);
        $children2 = new TestComponent('children', $children2);
        $span = new \Tests\Span('test_name', $children2);

        Assert::same($span, $master->lookup('Tests\Span'));
    }

    public function testNeedFalse()
    {
        $master = new TestComponent();
        new \Tests\Span('test_name', $master);

        Assert::null($master->lookup('NotExist\ClassName', FALSE));
    }

    public function testException()
    {
        Assert::exception(function() {
            $master = new TestComponent();
            new \Tests\Span('test_name', $master);

            $master->lookup('NotExist\ClassName');
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new ContainerTest();
$test->run();