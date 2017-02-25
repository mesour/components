<?php
/**
 * Mesour Components
 *
 * @license       LGPL-3.0 and BSD-3-Clause
 * @Copyright (c) 2017 Matous Nemec <http://mesour.com>
 */

namespace Mesour\ComponentsTests\Classes;

use Mesour;
use Mesour\Components\Localization\ITranslatable;
use Mesour\Components\Security\IAuthorised;
use Tester\Assert;

/**
 * @author  mesour <http://mesour.com>
 */
class Span extends Mesour\UI\Control implements IAuthorised, ITranslatable
{

	use Mesour\Components\Localization\Translatable;
	use Mesour\Components\Security\Authorised;

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
