<?php

use Tester\Assert;
use Tests\TestComponent;

require_once __DIR__ . '/../bootstrap.php';

class ComponentModelTest extends \Test\BaseTestCase
{

    public function testSetAndGetName()
    {
        $test = new TestComponent();
        Assert::same(NULL, $test->getName());
        $test->setName('test');
        Assert::same('test', $test->getName());

        $test2 = new TestComponent('test');
        Assert::same('test', $test2->getName());
    }

    public function testAddComponent()
    {
        $master = new TestComponent();
        $children = new TestComponent('children');

        $master->addComponent($children);

        Assert::same($children, $master->getComponent('children'));
        Assert::same($master, $children->getParent());
        Assert::count(1, $master->getComponents());

        // -----

        $master2 = new TestComponent();
        $children2 = new TestComponent('children2');

        $master2['children2'] = $children2;

        Assert::same($children2, $master2['children2']);
        Assert::same($master2, $children2->getParent());
        Assert::count(1, $master2->getComponents());

        // -----

        $master3 = new TestComponent();
        $children3 = new TestComponent('children3', $master3);

        Assert::same($children3, $master3['children3']);
        Assert::same($master3, $children3->getParent());
        Assert::count(1, $master3->getComponents());

        // -----

        $master4 = new TestComponent();
        $children4 = new TestComponent();

        $master4->addComponent($children4, 'children4');

        Assert::same($children4, $master4->getComponent('children4'));
        Assert::same($master4, $children4->getParent());
        Assert::count(1, $master4->getComponents());
    }

    public function testRemoveComponent()
    {
        $master = new TestComponent();
        $children = new TestComponent('children');

        $master->addComponent($children);
        $master->removeComponent('children');

        Assert::count(0, $master->getComponents());

        // -----

        $master2 = new TestComponent();
        $children2 = new TestComponent('children2');

        $master2['children2'] = $children2;
        unset($master2['children2']);

        Assert::count(0, $master2->getComponents());
    }

    public function testIterate()
    {
        $master = new TestComponent();
        new TestComponent('children', $master);
        new TestComponent('children2', $master);
        new TestComponent('children3', $master);

        Assert::count(3, $master->getComponents());

        foreach ($master->getComponents() as $name => $component) {
            Assert::same($name, $component->getName());
            Assert::same($master, $component->getParent());
        }

        foreach ($master as $name => $component) {
            Assert::same($name, $component->getName());
            Assert::same($master, $component->getParent());
        }
    }

    public function testAttachedDetached()
    {
        $master = new TestComponent();
        $children = new TestComponent('children', $master);

        $children->assertAttached($master);
        Assert::count(1, $master);

        $master->removeComponent('children');

        $children->assertDetached($master);
        Assert::count(0, $master);
    }

    public function testClone()
    {
        $master = new TestComponent('test1');
        new TestComponent('children', $master);
        new TestComponent('children2', $master);
        new TestComponent('children3', $master);

        $master2 = clone $master;
        $master2->setName('test2');

        Assert::same($master2->getComponent('children')->getParent(), $master2);
        Assert::count(3, $master->getComponents());
        Assert::count(3, $master2->getComponents());
        Assert::notSame($master->getComponent('children'), $master2->getComponent('children'));
        Assert::notSame($master->getComponent('children2'), $master2->getComponent('children2'));
        Assert::notSame($master->getComponent('children3'), $master2->getComponent('children3'));
    }

}

$test = new ComponentModelTest();
$test->run();