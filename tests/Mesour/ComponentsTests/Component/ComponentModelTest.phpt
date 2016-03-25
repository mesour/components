<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ComponentModelTest extends Mesour\Tests\BaseTestCase
{

	public function testSetAndGetName()
	{
		$test = new Classes\TestComponent();
		Assert::same(null, $test->getName());
		$test->setName('test');
		Assert::same('test', $test->getName());

		$test2 = new Classes\TestComponent('test');
		Assert::same('test', $test2->getName());
	}

	public function testAddComponent()
	{
		$master = new Classes\TestComponent();
		$children = new Classes\TestComponent('children');

		$master->addComponent($children);

		Assert::same($children, $master->getComponent('children'));
		Assert::same($master, $children->getParent());
		Assert::count(1, $master->getComponents());

		// -----

		$master2 = new Classes\TestComponent();
		$children2 = new Classes\TestComponent('children2');

		$master2['children2'] = $children2;

		Assert::same($children2, $master2['children2']);
		Assert::same($master2, $children2->getParent());
		Assert::count(1, $master2->getComponents());

		// -----

		$master3 = new Classes\TestComponent();
		$children3 = new Classes\TestComponent('children3', $master3);

		Assert::same($children3, $master3['children3']);
		Assert::same($master3, $children3->getParent());
		Assert::count(1, $master3->getComponents());

		// -----

		$master4 = new Classes\TestComponent();
		$children4 = new Classes\TestComponent();

		$master4->addComponent($children4, 'children4');

		Assert::same($children4, $master4->getComponent('children4'));
		Assert::same($master4, $children4->getParent());
		Assert::count(1, $master4->getComponents());
	}

	public function testRemoveComponent()
	{
		$master = new Classes\TestComponent();
		$children = new Classes\TestComponent('children');

		$master->addComponent($children);
		$master->removeComponent('children');

		Assert::count(0, $master->getComponents());

		// -----

		$master2 = new Classes\TestComponent();
		$children2 = new Classes\TestComponent('children2');

		$master2['children2'] = $children2;
		unset($master2['children2']);

		Assert::count(0, $master2->getComponents());
	}

	public function testIterate()
	{
		$master = new Classes\TestComponent();
		new Classes\TestComponent('children', $master);
		new Classes\TestComponent('children2', $master);
		new Classes\TestComponent('children3', $master);

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
		$master = new Classes\TestComponent();
		$children = new Classes\TestComponent('children', $master);

		$children->assertAttached($master);
		Assert::count(1, $master);

		$master->removeComponent('children');

		$children->assertDetached($master);
		Assert::count(0, $master);
	}

	public function testClone()
	{
		$master = new Classes\TestComponent('test1');
		new Classes\TestComponent('children', $master);
		new Classes\TestComponent('children2', $master);
		new Classes\TestComponent('children3', $master);

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
