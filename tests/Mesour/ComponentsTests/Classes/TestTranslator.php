<?php
/**
 * Mesour Components
 *
 * @license       LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\ComponentsTests\Classes;

use Mesour;

/**
 * @author  mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class TestTranslator implements Mesour\Components\Localization\ITranslator
{

    private $translates = [
        'translated' => 'new_string'
    ];

    public function translate($message, $count = NULL)
    {
        if (isset($this->translates[$message])) {
            return $this->translates[$message];
        }
        return $message;
    }

}
