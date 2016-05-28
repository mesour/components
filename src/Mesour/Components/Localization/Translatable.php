<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Localization;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method Mesour\UI\Application getApplication($need = true)
 * @method Mesour\Components\Control\BaseControl getParent()
 */
trait Translatable
{

	/**
	 * @var ITranslator|null
	 */
	private $translator;

	/**
	 * @var NullTranslator|null
	 */
	private $nullTranslator;

	/**
	 * @var bool
	 */
	private $disabledTranslate = false;

	/**
	 * @param bool|FALSE $fromChildren
	 * @return ITranslator
	 */
	public function getTranslator($fromChildren = false)
	{
		if ($this->disabledTranslate && !$fromChildren) {
			return $this->getNullTranslator();
		}
		if (!$this->translator && method_exists($this->getParent(), 'getTranslator')) {
			return $this->getParent()->getTranslator(true);
		}
		return $this->getRealTranslator();
	}

	/**
	 * @param bool $disabled
	 * @return self
	 */
	public function setTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
		return $this;
	}

	/**
	 * @param bool $disabled
	 * @return self
	 */
	public function setDisableTranslate($disabled = true)
	{
		$this->disabledTranslate = (bool) $disabled;
		return $this;
	}

	public function isDisabledTranslate()
	{
		return $this->disabledTranslate;
	}

	private function getNullTranslator()
	{
		$application = $this->getApplication(false);
		if ($application) {
			return $application->getContext()->getByType(NullTranslator::class);
		}
		if (!$this->nullTranslator) {
			$this->nullTranslator = new NullTranslator();
		}
		return $this->nullTranslator;
	}

	private function getRealTranslator()
	{
		if ($this->translator) {
			return $this->translator;
		}
		$application = $this->getApplication(false);
		if ($application) {
			return $application->getContext()->getByType(ITranslator::class);
		}

		return $this->translator = $this->getNullTranslator();
	}

}
