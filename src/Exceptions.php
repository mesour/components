<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Exception extends \Exception
{
}

class BadStateException extends Exception
{
}

class InvalidArgumentException extends Exception
{
}

class StaticClassException extends Exception
{

    protected $message = 'You can not create instance of static class.';

}
