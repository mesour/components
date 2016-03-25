<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTestLink extends Mesour\Tests\BaseTestCase
{

	public function testDefaultLink()
	{
		$span = new Classes\Span;

		$link = new Mesour\Components\Link\Link;

		$span->setLink($link);

		Assert::same($span->getLink(), $link);
	}

	public function testLinkMethod()
	{
		$span = new Classes\Span;

		$link = new Mesour\Components\Link\Link;
		$span->setLink($link);

		$address = 'http://mesour.com';
		$args = ['key' => 'val[]'];
		$completeAddress = 'http://mesour.com?key=val%5B%5D';

		Assert::same($span->getLink(), $link);

		$url = $span->link($address, $args);

		Assert::type(Mesour\Components\Link\IUrl::class, $url);
		Assert::same($url->create(), $completeAddress);
	}

	public function testLinkSetAndGet()
	{
		$span = new Classes\Span;

		$link = new Mesour\Components\Link\Link;

		$span->setLink($link);

		Assert::same($span->getLink(), $link);
	}

	public function testLinkFromParent()
	{
		$span = new Classes\Span;

		$link = new Mesour\Components\Link\Link;

		$span->setLink($link);

		$span->addComponent(new Classes\Span, 'children');

		Assert::same($span->getComponent('children')->getLink(), $link);
	}

}

$test = new ControlTestLink();
$test->run();
