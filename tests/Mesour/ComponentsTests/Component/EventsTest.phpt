<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class EventsTest extends Mesour\Tests\BaseTestCase
{

	private $called = false;

	public function testCall()
	{
		$test = new Classes\TestComponent();
		$test->onTest[] = function ($component) use ($test) {
			Assert::same($test, $component);
			$this->called = true;
		};
		$test->triggerTest();

		Assert::true($this->called);
		$this->called = false;
	}

	public function testNotArrayPropertyException()
	{
		Assert::exception(
			function () {
				$test = new Classes\TestComponent();

				$test->triggerNotArray();
			},
			Mesour\UnexpectedValueException::class
		);
	}

	public function testPrivatePropertyException()
	{
		Assert::exception(
			function () {
				$test = new Classes\TestComponent();

				$test->triggerPrivateProperty();
			},
			Mesour\InvalidStateException::class
		);
	}

	public function testNotDefinedPropertyException()
	{
		Assert::exception(
			function () {
				$test = new Classes\TestComponent();

				$test->triggerNotDefined();
			},
			Mesour\MethodCallException::class
		);
	}

}

$test = new EventsTest();
$test->run();
