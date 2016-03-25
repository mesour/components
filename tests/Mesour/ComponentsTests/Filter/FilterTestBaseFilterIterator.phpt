<?php

namespace Mesour\ComponentsTests\Filter;

use Mesour;
use Mesour\ComponentsTests\Classes;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class FilterTestBaseFilterIterator extends Mesour\Tests\BaseTestCase
{

	public function testBasicFind()
	{
		$master = new Classes\Span;

		$master->addComponent(new Classes\Span, 'test1');
		$master->addComponent(new Classes\Span, 'test2');
		$master->addComponent(new Classes\Span, 'test3');
		$master->addComponent(new Classes\Span, 'test4');
		$master->addComponent(new Classes\Span, 'test5');
		$master->addComponent(new Classes\Span, 'test6');
		$master->addComponent($test7 = new Classes\Span, 'test7');
		$master->addComponent(new Classes\Span, 'test8');
		$master->addComponent(new Classes\Span, 'test9');
		$master->addComponent(new Classes\Span, 'test10');

		$filter = $master->getFilter();
		$filter->addRule(Mesour\Components\ComponentModel\Filter\BaseRules::BY_NAME, 'test7');

		Assert::same($test7, $filter->fetch());
	}

	public function testNotFound()
	{
		$master = new Classes\Span;

		$master->addComponent(new Classes\Span, 'test1');
		$master->addComponent(new Classes\Span, 'test2');
		$master->addComponent(new Classes\Span, 'test3');

		$filter = $master->getFilter();
		$filter->addRule(Mesour\Components\ComponentModel\Filter\BaseRules::BY_NAME, 'test7');

		Assert::false($filter->fetch());
	}

	public function testUndefinedRule()
	{
		$master = new Classes\Span;

		$master->addComponent(new Classes\Span, 'test1');
		$master->addComponent(new Classes\Span, 'test2');
		$master->addComponent(new Classes\Span, 'test3');

		$filter = $master->getFilter();
		Assert::exception(
			function () use ($filter) {
				$filter->addRule('ghsgdf', 'test7');
			},
			Mesour\InvalidArgumentException::class
		);
	}

}

$test = new FilterTestBaseFilterIterator();
$test->run();
