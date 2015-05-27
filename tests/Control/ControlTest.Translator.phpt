<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ControlTest_Translator extends \Test\BaseTestCase
{

    public function testTranslatorSetAndGet()
    {
        $span = new \Tests\Span;

        $translator = new \Mesour\Components\Localize\Translator;

        $span->setTranslator($translator);

        Assert::same($span->getTranslator(), $translator);
    }

    public function testTranslatorFromParent()
    {
        $span = new \Tests\Span;

        $translator = new \Mesour\Components\Localize\Translator;

        $span->setTranslator($translator);

        $span->addComponent(new \Tests\Span, 'children');

        Assert::same($span->getComponent('children')->getTranslator(), $translator);
    }

}

$test = new ControlTest_Translator();
$test->run();