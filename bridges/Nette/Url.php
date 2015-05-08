<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Bridges\Nette;

use Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 *
 * @property-read Link $link
 */
class Url extends Components\Link\Url
{

    protected function createUrl()
    {
        return $this->link->link($this->destination, $this->args);
    }

}
