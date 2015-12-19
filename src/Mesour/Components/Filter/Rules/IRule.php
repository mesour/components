<?php

namespace Mesour\Components\Filter\Rules;


use Mesour;

interface IRule
{

    public function getName();

    public function isMatch(Mesour\Components\ComponentModel\IComponent $component, $value, array $parameters = []);

}