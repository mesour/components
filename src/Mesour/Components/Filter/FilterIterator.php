<?php

namespace Mesour\Components\Filter;


use Mesour;

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
            throw new Mesour\InvalidStateException(sprintf('No rules are set. Use %s::setRulesContainer method.', get_class($this)));
        }
        return $this->rulesContainer;
    }

    public function addRule($ruleName, $searchedValue, array $parameters = [])
    {
        if (!$this->getRulesContainer()->hasRule($ruleName)) {
            throw new Mesour\InvalidArgumentException("Rule with name $ruleName is not allowed.");
        }
        $this->rules[] = [$ruleName, $searchedValue, $parameters];
    }

    protected function lookup($current, FilterIterator $filter)
    {
        foreach ($this->rules as $rule) {
            list($name, $value, $parameters) = $rule;
            $ruleInstance = $this->getRulesContainer()->getRule($name);

            $verified = $ruleInstance->isMatch($current, $value, $parameters);

            if (!$verified) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function accept()
    {
        return $this->lookup($this->current(), $this);
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
        return FALSE;
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
