<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTestLink extends Mesour\Tests\BaseTestCase
{

	public function testDefaultLink()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$link = new Mesour\Components\Link\Link;

		$application->getContext()->setService($link, Mesour\Components\Link\ILink::class);

		Assert::same($span->getLink(), $link);
	}

	public function testLinkMethod()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$link = new Mesour\Components\Link\Link;
		$application->getContext()->setService($link, Mesour\Components\Link\ILink::class);

		$address = 'http://mesour.com';
		$args = ['key' => 'val[]'];
		$completeAddress = 'http://mesour.com?key=val%5B%5D';

		Assert::same($span->getLink(), $link);

		$url = $span->link($address, $args);

		Assert::type(Mesour\Components\Link\IUrl::class, $url);
		Assert::same($completeAddress, $url->create());
	}

	public function testLinkSetAndGet()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$link = new Mesour\Components\Link\Link;

		$application->getContext()->setService($link, Mesour\Components\Link\ILink::class);

		Assert::same($link, $span->getLink());
	}

	public function testLinkFromParent()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$link = new Mesour\Components\Link\Link;

		$application->getContext()->setService($link, Mesour\Components\Link\ILink::class);

		$span->addComponent(new Classes\Span, 'children');

		/** @var Mesour\ComponentsTests\Classes\Span $children */
		$children = $span->getComponent('children');
		Assert::same($children->getLink(), $link);
	}

}

$test = new ControlTestLink();
$test->run();
