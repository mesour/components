<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class ControlTest_Translator extends Mesour\Tests\BaseTestCase
{

    public function testTranslatorSetAndGet()
    {
        $span = new Classes\Span;

        $translator = new Mesour\Components\Localization\NullTranslator;

        $span->setTranslator($translator);

        Assert::same($span->getTranslator(), $translator);
    }

    public function testTranslatorFromParent()
    {
        $span = new Classes\Span;

        $translator = new Mesour\Components\Localization\NullTranslator;

        $span->setTranslator($translator);

        $span->addComponent(new Classes\Span, 'children');

        Assert::same($span->getComponent('children')->getTranslator(), $translator);
    }

}

$test = new ControlTest_Translator();
$test->run();