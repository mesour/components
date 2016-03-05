<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTest_Auth extends Mesour\Tests\BaseTestCase
{

	public function testAuthSetAndGet()
	{
		$span = new Classes\Span;

		$auth = new Mesour\Components\Security\Permission;

		$auth->addRole('guest');

		$auth->allow('guest');

		$span->setAuthorizator($auth);

		Assert::same($span->getAuthorizator(), $auth);
		Assert::true($span->getAuthorizator()->isAllowed('guest'));
	}

	public function testAuthFromParent()
	{
		$span = new Classes\Span;

		$auth = new Mesour\Components\Security\Permission;

		$span->setAuthorizator($auth);

		$span->addComponent(new Classes\Span, 'children');

		Assert::same($span->getComponent('children')->getAuthorizator(), $auth);
	}

}

$test = new ControlTest_Auth();
$test->run();