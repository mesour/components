<?php
/**
 * Mesour Components
 *
 * @license       LGPL-3.0 and BSD-3-Clause
 * @Copyright (c) 2017 Matous Nemec <http://mesour.com>
 */

namespace Mesour\ComponentsTests\Classes;

use Mesour;
use Tester\Assert;

/**
 * @author  mesour <http://mesour.com>
 * @package Mesour Components
 */
class TestComponent extends Mesour\Components\ComponentModel\Container
{

	public $onTest = [];

	public $onNotArray;

	private $onPrivate = [];

	/**
	 * @var Mesour\Components\ComponentModel\IContainer
	 */
	public $attachedContains;

	/**
	 * @var Mesour\Components\ComponentModel\IContainer
	 */
	public $detachedContains;

	public function triggerTest()
	{
		$this->onTest($this);
	}

	public function triggerNotArray()
	{
		$this->onNotArray();
	}

	public function triggerPrivateProperty()
	{
		$this->onPrivate();
	}

	public function triggerNotDefined()
	{
		$this->onNonDefined();
	}

	public function attached(Mesour\Components\ComponentModel\IContainer $parent)
	{
		parent::attached($parent);
		$this->attachedContains = $parent;
		$this->onPrivate = $this->onPrivate;
	}

	/**
	 * @param Mesour\Components\ComponentModel\IContainer $parent
	 */
	public function detached(Mesour\Components\ComponentModel\IContainer $parent)
	{
		parent::attached($parent);
		$this->detachedContains = $parent;
	}

	public function assertAttached(Mesour\Components\ComponentModel\IContainer $parent)
	{
		Assert::same($this->attachedContains, $parent);
	}

	public function assertDetached(Mesour\Components\ComponentModel\IContainer $parent)
	{
		Assert::same($this->detachedContains, $parent);
	}

}
