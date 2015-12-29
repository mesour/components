<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\ComponentModel\Filter;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
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
