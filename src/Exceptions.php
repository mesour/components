<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Exception extends \Exception
{
}

class BadStateException extends Exception
{
}

class NotSupportedException extends Exception
{
}

class InvalidArgumentException extends Exception
{
}

class StaticClassException extends Exception
{

    protected $message = 'You can not create instance of static class.';

}
