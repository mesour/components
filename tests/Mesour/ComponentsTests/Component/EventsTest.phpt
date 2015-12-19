<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class EventsTest extends Mesour\Tests\BaseTestCase
{

    private $called = FALSE;

    public function testCall()
    {
        $test = new Classes\TestComponent();
        $test->onTest[] = function ($component) use ($test) {
            Assert::same($test, $component);
            $this->called = TRUE;
        };
        $test->triggerTest();

        Assert::true($this->called);
        $this->called = FALSE;
    }

    public function testNotArrayPropertyException()
    {
        Assert::exception(function () {
            $test = new Classes\TestComponent();

            $test->triggerNotArray();
        }, Mesour\UnexpectedValueException::class);
    }

    public function testPrivatePropertyException()
    {
        Assert::exception(function () {
            $test = new Classes\TestComponent();

            $test->triggerPrivateProperty();
        }, Mesour\InvalidStateException::class);
    }

    public function testNotDefinedPropertyException()
    {
        Assert::exception(function () {
            $test = new Classes\TestComponent();

            $test->triggerNotDefined();
        }, Mesour\MethodCallException::class);
    }

}

$test = new EventsTest();
$test->run();