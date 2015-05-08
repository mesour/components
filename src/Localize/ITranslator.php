<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Localize;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface ITranslator
{

    /**
     * Translates the given string.
     * @param string $message
     * @param int $count
     * @return string
     */
    public function translate($message, $count = NULL);

}
