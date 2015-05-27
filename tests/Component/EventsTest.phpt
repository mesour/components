<?php

use Tester\Assert;
use Tests\TestComponent;

require_once __DIR__ . '/../bootstrap.php';

class EventsTest extends \Test\BaseTestCase
{

    private $called = FALSE;

    public function testCall()
    {
        $test = new TestComponent();
        $test->onTest[] = function($component) use($test) {
            Assert::same($test, $component);
            $this->called = TRUE;
        };
        $test->triggerTest();

        Assert::true($this->called);
        $this->called = FALSE;
    }

    public function testNotArrayPropertyException()
    {
        Assert::exception(function() {
            $test = new TestComponent();

            $test->triggerNotArray();
        }, 'Mesour\Components\InvalidArgumentException');
    }

    public function testPrivatePropertyException()
    {
        Assert::exception(function() {
            $test = new TestComponent();

            $test->triggerPrivateProperty();
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new EventsTest();
$test->run();