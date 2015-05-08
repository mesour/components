<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Link;

use Mesour\Components\Helper;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Link implements ILink
{

    public function create($destination, $args = array())
    {
        return new Url($this, $destination, $args);
    }

}
