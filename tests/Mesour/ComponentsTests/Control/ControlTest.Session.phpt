<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTest_Session extends Mesour\Tests\BaseTestCase
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

$test = new ControlTest_Session();
$test->run();