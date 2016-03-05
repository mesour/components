<?php

namespace Mesour\ComponentsTests;

use Mesour\Tests\BaseTestCase;
use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class SessionTest extends BaseTestCase
{

	public function testSetAndGetSections()
	{
		$session = new Mesour\Components\Session\Session;

		$test = $session->getSection('test');

		Assert::true($session->hasSection('test'));
		Assert::type(Mesour\Components\Session\ISessionSection::class, $test);
	}

	public function testSetAndGetValue()
	{
		$session = new Mesour\Components\Session\Session;
		$test = $session->getSection('test');

		$testValue = 'test_text';

		$test->set('test', $testValue);

		Assert::same($test->get('test'), $testValue);
		Assert::same($test->get('unknown_key', 'default'), 'default');
	}

	public function testExceptions()
	{
		Assert::exception(function () {
			$session = new Mesour\Components\Session\Session;
			$session->getSection([]);
		}, Mesour\InvalidArgumentException::class);

		Assert::exception(function () {
			$session = new Mesour\Components\Session\Session;
			$test = $session->getSection('test');

			$test->set([], true);
		}, Mesour\InvalidArgumentException::class);
	}

}

$test = new SessionTest();
$test->run();