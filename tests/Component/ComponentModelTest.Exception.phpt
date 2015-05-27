<?php

use Tester\Assert;
use Tests\TestComponent;

require_once __DIR__ . '/../bootstrap.php';

class ComponentModelTest_Exception extends \Test\BaseTestCase
{

    public function testSetName()
    {
        Assert::exception(function() {
            new TestComponent(array());
        }, 'Mesour\Components\InvalidArgumentException');

        Assert::exception(function() {
            $test2 = new TestComponent();
            $test2->setName(array());
        }, 'Mesour\Components\InvalidArgumentException');
    }

    public function testAddComponent()
    {
        Assert::exception(function() {
            $master = new TestComponent();
            $children = new TestComponent();

            $master->addComponent($children, array());
        }, 'Mesour\Components\InvalidArgumentException');
    }

    public function testGetComponent()
    {
        Assert::exception(function() {
            $master = new TestComponent();
            $master->getComponent('not_exist_component');
        }, 'Mesour\Components\InvalidArgumentException');
    }

    public function testRemoveComponent()
    {
        Assert::exception(function() {
            $master = new TestComponent();
            $master->removeComponent(array());
        }, 'Mesour\Components\InvalidArgumentException');

        Assert::exception(function() {
            $master = new TestComponent();
            $master->removeComponent('not_exist_component');
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new ComponentModelTest_Exception();
$test->run();