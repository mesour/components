<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Session;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface ISession
{

    public function getEmptyClone($section);

    public function set($key, $val);

    public function get($key);

    public function loadState();

    public function saveState();

    public function getSection();

}
