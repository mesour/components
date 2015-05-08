<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Link;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface IUrl
{

    public function __construct(ILink $link, $destination, $args = array());

    public function create($data = array());

    /**
     * @return ILink
     */
    public function getLink();

    /**
     * @return string
     */
    public function getDestination();

    /**
     * @return array
     */
    public function getArguments();

    public function __toString();

}
