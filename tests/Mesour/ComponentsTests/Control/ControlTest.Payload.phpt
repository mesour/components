<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTest_Payload extends Mesour\Tests\BaseTestCase
{

	public function testPayloadSetAndGet()
	{
		$span = new Classes\Span;

		$payload = new Mesour\Components\Application\Payload;

		$span->setPayload($payload);

		Assert::same($span->getPayload(), $payload);
	}

	public function testPayloadFromParent()
	{
		$span = new Classes\Span;

		$payload = new Mesour\Components\Application\Payload;

		$span->setPayload($payload);

		$span->addComponent(new Classes\Span, 'children');

		Assert::same($span->getComponent('children')->getPayload(), $payload);
	}

}

$test = new ControlTest_Payload();
$test->run();