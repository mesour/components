<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ComponentModelTestException extends Mesour\Tests\BaseTestCase
{

	public function testSetName()
	{
		Assert::exception(
			function () {
				new Classes\TestComponent('non-alphanumeric');
			},
			Mesour\InvalidArgumentException::class
		);

		Assert::exception(
			function () {
				new Classes\TestComponent([]);
			},
			Mesour\InvalidArgumentException::class
		);

		Assert::exception(
			function () {
				$test2 = new Classes\TestComponent();
				$test2->setName([]);
			},
			Mesour\InvalidArgumentException::class
		);
	}

	public function testAddComponent()
	{
		Assert::exception(
			function () {
				$master = new Classes\TestComponent();
				$children = new Classes\TestComponent();

				$master->addComponent($children, []);
			},
			Mesour\InvalidArgumentException::class
		);
	}

	public function testGetComponent()
	{
		Assert::exception(
			function () {
				$master = new Classes\TestComponent();
				$master->getComponent('not_exist_component');
			},
			Mesour\InvalidStateException::class
		);
	}

	public function testRemoveComponent()
	{
		Assert::exception(
			function () {
				$master = new Classes\TestComponent();
				$master->removeComponent([]);
			},
			Mesour\InvalidArgumentException::class
		);

		Assert::exception(
			function () {
				$master = new Classes\TestComponent();
				$master->removeComponent('not_exist_component');
			},
			Mesour\InvalidStateException::class
		);
	}

}

$test = new ComponentModelTestException();
$test->run();
