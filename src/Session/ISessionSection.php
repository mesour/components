<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Session;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface ISessionSection
{

    public function set($key, $val);

    public function get($key = NULL);

    public function loadState($data);

}
