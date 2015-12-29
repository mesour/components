<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Filter\Rules;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
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
