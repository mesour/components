<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTestPayload extends Mesour\Tests\BaseTestCase
{

	public function testPayloadSetAndGet()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$payload = new Mesour\Components\Application\Payload;

		$application->getContext()->setService($payload, Mesour\Components\Application\IPayload::class);

		Assert::same($payload, $span->getPayload());
	}

	public function testPayloadFromParent()
	{
		$application = new Mesour\UI\Application;

		$span = new Classes\Span('testSpan', $application);

		$payload = new Mesour\Components\Application\Payload;

		$application->getContext()->setService($payload, Mesour\Components\Application\IPayload::class);

		$span->addComponent(new Classes\Span, 'children');

		/** @var Mesour\ComponentsTests\Classes\Span $children */
		$children = $span->getComponent('children');
		Assert::same($payload, $children->getPayload());
	}

}

$test = new ControlTestPayload();
$test->run();
