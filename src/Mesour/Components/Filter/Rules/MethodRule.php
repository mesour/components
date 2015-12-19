<?php

namespace Mesour\Components\Filter\Rules;


use Mesour;

class MethodRule extends Rule
{

    private $methodName;

    public function __construct($name, $methodName = NULL)
    {
        parent::__construct($name);

        if (!preg_match('/^[a-zA-Z_]{1}[a-zA-Z0-9_]*$/', $methodName)) {
            throw new Mesour\InvalidArgumentException(sprintf('Method name must be non empty string. %s given.'), gettype($methodName));
        }
        $this->methodName = $methodName;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function isMatch(Mesour\Components\ComponentModel\IComponent $component, $value, array $parameters = [])
    {
        return Mesour\Components\Utils\Helpers::invokeArgs([$component, $this->getMethodName()], $parameters) === $value;
    }

}