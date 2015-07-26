<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Link;

use Mesour\Components\InvalidArgumentException;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
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
