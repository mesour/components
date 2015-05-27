<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface IPayload
{

    /**
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key = NULL, $default = NULL);

    public function sendPayload();

}
