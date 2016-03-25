<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTest extends Mesour\Tests\BaseTestCase
{

	public function testCreateSnippetMethod()
	{
		$span = new Classes\Span('test_span');

		$div = Mesour\Components\Utils\Html::el('div', ['id' => Mesour\UI\Control::SNIPPET_PREFIX . $span->createLinkName()]);

		Assert::equal($span->createSnippet(), $div);
	}

	public function testSetTextMethod()
	{
		$span = new Classes\Span;

		$span->setText('Test span text.');

		Assert::same('<span>Test span text.</span>', (string) $span->create());
	}

}

$test = new ControlTest();
$test->run();
