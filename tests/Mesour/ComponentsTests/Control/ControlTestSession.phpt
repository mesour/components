<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTestSession extends Mesour\Tests\BaseTestCase
{

	public function testSessionSetAndGet()
	{
		$span = new Classes\Span;

		$session = new Mesour\Components\Session\Session;

		$span->setSession($session);

		Assert::same($span->getSession(), $session);
	}

	public function testSessionFromParent()
	{
		$span = new Classes\Span;

		$session = new Mesour\Components\Session\Session;

		$span->setSession($session);

		$span->addComponent(new Classes\Span, 'children');

		Assert::same($span->getComponent('children')->getSession(), $session);
	}

}

$test = new ControlTestSession();
$test->run();
