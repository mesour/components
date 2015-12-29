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