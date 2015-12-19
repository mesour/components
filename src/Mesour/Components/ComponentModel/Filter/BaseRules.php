<?php

namespace Mesour\Components\ComponentModel\Filter;


use Mesour;

class BaseRules extends Mesour\Components\Filter\Rules\RulesContainer
{

    const BY_NAME = 'base.byName';

    public function __construct()
    {
        $this->setRule(new Mesour\Components\Filter\Rules\MethodRule(
            self::BY_NAME, 'getName'
        ));
    }

}
