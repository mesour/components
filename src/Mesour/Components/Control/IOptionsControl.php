<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IOptionsControl extends IControl
{

    public function setOption($key, $value, $subKey = NULL);

    public function getOption($key, $subKey = NULL);

    public function hasOption($key);

}
