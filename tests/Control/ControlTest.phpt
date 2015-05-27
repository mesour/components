<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ControlTest extends \Test\BaseTestCase
{

    public function testCreateSnippetMethod()
    {
        $span = new \Tests\Span('test_span');

        $div = \Mesour\Components\Html::el('div', array('id' => \Mesour\UI\Control::SNIPPET_PREFIX . $span->createLinkName()));

        Assert::equal($span->createSnippet(), $div);
    }

    public function testSetTextMethod()
    {
        $span = new \Tests\Span;

        $span->setText('Test span text.');

        Assert::same('<span>Test span text.</span>', (string)$span->create());
    }

}

$test = new ControlTest();
$test->run();