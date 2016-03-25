<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Filter;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class FilterIterator extends \FilterIterator
{

	/** @var Rules\IRule[] */
	protected $rules = [];

	/** @var Rules\RulesContainer */
	protected $rulesContainer = [];

	public function setRulesContainer(Rules\RulesContainer $rulesContainer)
	{
		$this->rulesContainer = $rulesContainer;
	}

	/**
	 * @return Rules\RulesContainer
	 */
	public function getRulesContainer()
	{
		if (!$this->rulesContainer) {
			throw new Mesour\InvalidStateException(
				sprintf('No rules are set. Use %s::setRulesContainer method.', get_class($this))
			);
		}
		return $this->rulesContainer;
	}

	public function addRule($ruleName, $searchedValue, array $parameters = [])
	{
		if (!$this->getRulesContainer()->hasRule($ruleName)) {
			throw new Mesour\InvalidArgumentException(
				sprintf('Rule with name %s is not allowed.', $ruleName)
			);
		}
		$this->rules[] = [$ruleName, $searchedValue, $parameters];
	}

	protected function lookup($current)
	{
		foreach ($this->rules as $rule) {
			list($name, $value, $parameters) = $rule;
			$ruleInstance = $this->getRulesContainer()->getRule($name);

			$verified = $ruleInstance->isMatch($current, $value, $parameters);

			if (!$verified) {
				return false;
			}
		}
		return true;
	}

	public function accept()
	{
		return $this->lookup($this->current());
	}

	public function fetchAll()
	{
		$out = [];
		foreach ($this as $item) {
			$out[] = $item;
		}
		return $out;
	}

	public function fetch()
	{
		foreach ($this as $item) {
			return $item;
		}
		return false;
	}

	public function fetchPairs($key, $value)
	{
		$out = [];
		foreach ($this as $item) {
			$out[$item[$key]] = $item[$value];
		}
		return $out;
	}

}
