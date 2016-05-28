<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTestSession extends Mesour\Tests\BaseTestCase
{

	public function testSessionSetAndGet()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$session = new Mesour\Components\Session\Session;

		$application->getContext()->setService($session, Mesour\Components\Session\ISession::class);

		Assert::same($session, $span->getSession());
	}

	public function testSessionFromParent()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$session = new Mesour\Components\Session\Session;

		$application->getContext()->setService($session, Mesour\Components\Session\ISession::class);

		$span->addComponent(new Classes\Span, 'children');

		/** @var Mesour\ComponentsTests\Classes\Span $children */
		$children = $span->getComponent('children');
		Assert::same($session, $children->getSession());
	}

}

$test = new ControlTestSession();
$test->run();
