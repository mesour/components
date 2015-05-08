<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Link;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface ILink
{

    /**
     * @param $destination
     * @param array $args
     * @return Url
     */
    public function create($destination, $args = array());

}
