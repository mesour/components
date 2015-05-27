<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Link;

use Mesour\Components\InvalidArgumentException;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Link implements ILink
{

    /**
     * @param $destination
     * @param array $args
     * @return Url
     * @throws InvalidArgumentException
     */
    public function create($destination, $args = array())
    {
        if (!is_string($destination)) {
            throw new InvalidArgumentException('Destination must be string. ' . gettype($destination) . ' given.');
        }
        return new Url($this, $destination, $args);
    }

}
