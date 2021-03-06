<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ContainerTest extends Mesour\Tests\BaseTestCase
{

	public function testOnComponent()
	{
		$master = new Classes\TestComponent();
		$span = new Classes\Span('test_name', $master);

		Assert::same($span, $master->lookup(Classes\Span::class));
	}

	public function testNestedInChildren()
	{
		$master = new Classes\TestComponent();
		$children = new Classes\TestComponent('children', $master);
		$children2 = new Classes\TestComponent('children', $children);
		$children2 = new Classes\TestComponent('children', $children2);
		$span = new Classes\Span('test_name', $children2);

		Assert::same($span, $master->lookup(Classes\Span::class));
	}

	public function testNeedFalse()
	{
		$master = new Classes\TestComponent();
		new Classes\Span('test_name', $master);

		Assert::null($master->lookup('NotExist\ClassName', false));
	}

	public function testException()
	{
		Assert::exception(
			function () {
				$master = new Classes\TestComponent();
				new Classes\Span('test_name', $master);

				$master->lookup('NotExist\ClassName');
			},
			Mesour\Components\NotFoundException::class
		);
	}

	public function testReversedOnComponent()
	{
		$span = new Classes\Span();
		$test = new Classes\TestComponent('test_name', $span);

		Assert::same($span, $test->lookup(Classes\Span::class, true, true));
	}

	public function testReversedNoParent()
	{
		$span = new Classes\Span();

		Assert::null($span->lookup(Classes\Span::class, false, true));
	}

	public function testReversedNestedInChildren()
	{
		$master = new Classes\TestComponent();
		$span = new Classes\Span('test_name', $master);
		$children = new Classes\TestComponent('children', $span);
		$children2 = new Classes\TestComponent('children', $children);
		$children2 = new Classes\TestComponent('children', $children2);
		$children3 = new Classes\Span('test_name', $children2);

		Assert::same($span, $children3->lookup(Classes\Span::class, true, true));
	}

	public function testReversedNeedFalse()
	{
		$master = new Classes\Span();
		$children = new Classes\TestComponent('test_name', $master);

		Assert::null($children->lookup('NotExist\ClassName', false));
		Assert::null($master->lookup('NotExist\ClassName', false));
	}

	public function testReversedException()
	{
		Assert::exception(
			function () {
				$master = new Classes\Span();
				$children = new Classes\TestComponent('test_name', $master);

				$children->lookup('NotExist\ClassName');
			},
			Mesour\Components\NotFoundException::class
		);
	}

}

$test = new ContainerTest();
$test->run();
