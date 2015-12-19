<?php

namespace Mesour\Components\Filter\Rules;


use Mesour;

abstract class RulesContainer extends Mesour\Object
{

    /** @var IRule[] */
    private $rules = [];

    public function getRules()
    {
        return $this->rules;
    }

    public function setRule(IRule $rule)
    {
        $this->rules[$rule->getName()] = $rule;
        return $this;
    }

    public function getRule($ruleName)
    {
        if (!$this->hasRule($ruleName)) {
            throw new Mesour\Components\NotFoundException("Rule with $ruleName doest not exists.");
        }
        return $this->rules[$ruleName];
    }

    public function hasRule($ruleName)
    {
        return isset($this->rules[$ruleName instanceof IRule ? $ruleName->getName() : $ruleName]);
    }

}
