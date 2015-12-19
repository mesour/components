<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class ComponentModelTest_Exception extends Mesour\Tests\BaseTestCase
{

    public function testSetName()
    {
        Assert::exception(function () {
            new Classes\TestComponent('non-alphanumeric');
        }, Mesour\InvalidArgumentException::class);

        Assert::exception(function () {
            new Classes\TestComponent([]);
        }, Mesour\InvalidArgumentException::class);

        Assert::exception(function () {
            $test2 = new Classes\TestComponent();
            $test2->setName([]);
        }, Mesour\InvalidArgumentException::class);
    }

    public function testAddComponent()
    {
        Assert::exception(function () {
            $master = new Classes\TestComponent();
            $children = new Classes\TestComponent();

            $master->addComponent($children, []);
        }, Mesour\InvalidArgumentException::class);
    }

    public function testGetComponent()
    {
        Assert::exception(function () {
            $master = new Classes\TestComponent();
            $master->getComponent('not_exist_component');
        }, Mesour\InvalidStateException::class);
    }

    public function testRemoveComponent()
    {
        Assert::exception(function () {
            $master = new Classes\TestComponent();
            $master->removeComponent([]);
        }, Mesour\InvalidArgumentException::class);

        Assert::exception(function () {
            $master = new Classes\TestComponent();
            $master->removeComponent('not_exist_component');
        }, Mesour\InvalidStateException::class);
    }

}

$test = new ComponentModelTest_Exception();
$test->run();