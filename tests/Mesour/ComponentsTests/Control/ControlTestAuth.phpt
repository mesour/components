<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTestAuth extends Mesour\Tests\BaseTestCase
{

	public function testAuthSetAndGet()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$auth = new Mesour\Components\Security\Permission;

		$auth->addRole('guest');

		$auth->allow('guest');

		$application->getContext()->setService($auth, Mesour\Components\Security\IAuthorizator::class);

		Assert::same($auth, $span->getAuthorizator());
		Assert::true($span->getAuthorizator()->isAllowed('guest'));
	}

	public function testAuthFromParent()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$auth = new Mesour\Components\Security\Permission;

		$application->getContext()->setService($auth, Mesour\Components\Security\IAuthorizator::class);

		$span->addComponent(new Classes\Span, 'children');

		/** @var Mesour\ComponentsTests\Classes\Span $children */
		$children = $span->getComponent('children');
		Assert::same($auth, $children->getAuthorizator());
	}

}

$test = new ControlTestAuth();
$test->run();
