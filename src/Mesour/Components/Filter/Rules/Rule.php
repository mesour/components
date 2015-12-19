<?php

namespace Mesour\Components\Filter\Rules;


use Mesour;

abstract class Rule extends Mesour\Object implements IRule
{

    private $name;

    public function __construct($name)
    {
        if (!is_string($name) || strlen($name) === 0) {
            throw new Mesour\InvalidArgumentException(sprintf('Rule name must be non empty string. %s given.'), gettype($name));
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    abstract public function isMatch(Mesour\Components\ComponentModel\IComponent $component, $value, array $parameters = []);

}