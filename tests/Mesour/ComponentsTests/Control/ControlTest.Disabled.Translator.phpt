<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTest_Disabled_Translator extends Mesour\Tests\BaseTestCase
{

	public function testTranslatorSetAndGet()
	{
		$span = new Classes\Span;

		$translator = new Classes\TestTranslator;

		$span->setTranslator($translator);

		$span->setText('translated');

		Assert::same($translator, $span->getTranslator());
		Assert::same('new_string', $span->getText());

		$span->setDisableTranslate();

		Assert::type(Mesour\Components\Localization\NullTranslator::class, $span->getTranslator());

		$span->setText('translated');

		Assert::same('translated', $span->getText());
	}

	public function testTranslatorFromParent()
	{
		$span = new Classes\Span;

		$translator = new Classes\TestTranslator;

		$span->setTranslator($translator);

		$children = new Classes\Span;
		$span->addComponent($children, 'children');

		Assert::same($children->getTranslator(), $translator);

		$children->setText('translated');

		Assert::same($translator, $children->getTranslator());
		Assert::same('new_string', $children->getText());

		$children->setDisableTranslate();

		Assert::type(Mesour\Components\Localization\NullTranslator::class, $children->getTranslator());

		$children->setText('translated');

		Assert::same('translated', $children->getText());
	}

}

$test = new ControlTest_Disabled_Translator();
$test->run();