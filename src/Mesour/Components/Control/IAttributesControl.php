<?php
/**
 * This file is part of the Mesour Button (http://components.mesour.com/component/button)
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
interface IAttributesControl extends IOptionsControl
{

    public function setAttributes(array $attributes);

    public function setAttribute($key, $value, $append = FALSE, $translated = FALSE);

    /**
     * @param bool|FALSE $isDisabled
     * @return array
     * @throws Mesour\InvalidArgumentException
     */
    public function getAttributes($isDisabled = FALSE);

    public function getAttribute($key, $need = TRUE);

}
