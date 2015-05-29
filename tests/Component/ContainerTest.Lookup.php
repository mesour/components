<?php

use Tester\Assert;
use Tests\TestComponent;

require_once __DIR__ . '/../bootstrap.php';

class ContainerTest extends \Test\BaseTestCase
{

    /* NORMAL */

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

    /* REVERSE */

    public function testReversedOnComponent()
    {
        $span = new \Tests\Span();
        $test = new TestComponent('test_name', $span);

        Assert::same($span, $test->lookup('Tests\Span', TRUE, TRUE));
    }

    public function testReversedNoParent()
    {
        $span = new \Tests\Span();

        Assert::null($span->lookup('Tests\Span', FALSE, TRUE));
    }

    public function testReversedNestedInChildren()
    {
        $master = new TestComponent();
        $span = new \Tests\Span('test_name', $master);
        $children = new TestComponent('children', $span);
        $children2 = new TestComponent('children', $children);
        $children2 = new TestComponent('children', $children2);
        $children3 = new \Tests\Span('test_name', $children2);

        Assert::same($span, $children3->lookup('Tests\Span', TRUE, TRUE));
    }

    public function testReversedNeedFalse()
    {
        $master = new \Tests\Span();
        $children = new TestComponent('test_name', $master);

        Assert::null($children->lookup('NotExist\ClassName', FALSE));
        Assert::null($master->lookup('NotExist\ClassName', FALSE));
    }

    public function testReversedException()
    {
        Assert::exception(function() {
            $master = new \Tests\Span();
            $children = new TestComponent('test_name', $master);

            $children->lookup('NotExist\ClassName');
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new ContainerTest();
$test->run();