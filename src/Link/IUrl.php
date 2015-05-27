<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Link;

use Mesour\Components\IString;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface IUrl extends IString
{

    /**
     * @param ILink $link
     * @param string $destination
     * @param array $args
     */
    public function __construct(ILink $link, $destination, $args = array());

    /**
     * @param array $data
     * @return string
     */
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

}
