<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class LinkTest extends Mesour\Tests\BaseTestCase
{

	public function testCreate()
	{
		$link = new Mesour\Components\Link\Link;

		$created = $link->create('http://mesour.com');

		Assert::type(Mesour\Components\Link\IUrl::class, $created);
		Assert::same('http://mesour.com', $created->create());
	}

	public function testCreateWithArguments()
	{
		$link = new Mesour\Components\Link\Link;

		$created = $link->create('http://mesour.com', ['key' => 'val', 'test' => 1]);

		Assert::count(2, $created->getArguments());
		Assert::same('http://mesour.com?key=val&test=1', $created->create());
	}

	public function testBadDestinationException()
	{
		Assert::exception(function () {
			$link = new Mesour\Components\Link\Link;

			$link->create([]);
		}, Mesour\InvalidArgumentException::class);
	}

}

$test = new LinkTest();
$test->run();