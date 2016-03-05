<?php
/**
 * Mesour Components
 *
 * @license       LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\ComponentsTests\Classes;

use Mesour;
use Tester\Assert;

/**
 * @author  mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Span extends Mesour\UI\Control
{

	/**
	 * @var Mesour\Components\Utils\Html
	 */
	private $wrapper;

	private $text = 'Default content';

	private $handleCalled = false;

	/**
	 * @return Mesour\Components\Utils\Html
	 */
	public function getControlPrototype()
	{
		return !$this->wrapper ? ($this->wrapper = Mesour\Components\Utils\Html::el('span')) : $this->wrapper;
	}

	public function setText($text)
	{
		$this->text = $this->getTranslator()->translate($text);
		return $this;
	}

	public function getText()
	{
		return $this->text;
	}

	public function create()
	{
		parent::create();

		$wrapper = $this->getControlPrototype();
		$wrapper->setText($this->text);

		return $wrapper;
	}

	public function handleChange($page)
	{
		$this->handleCalled = true;
		Assert::same($page, '2');
	}

	public function getFilter()
	{
		return $this->createFilterIterator();
	}

	public function handleChangeArray($page = [])
	{
		$this->handleCalled = true;
		Assert::same($page, Mesour\ComponentsTests\HandlesTest::$testArr);
	}

	public function handleChangeArrayRequired(array $page)
	{
		$this->handleCalled = true;
		Assert::same($page, Mesour\ComponentsTests\HandlesTest::$testArr);
	}

	public function assertHandleCalled()
	{
		Assert::true($this->handleCalled);
	}

}
