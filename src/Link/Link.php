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
class Link implements ILink
{

    public function link($destination, $args = array()) {
        $query = http_build_query($args);
        dump($destination, $query);
        die;
    }

}
