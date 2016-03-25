<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour;

/**
 * The exception that is thrown when the value of an argument is
 * outside the allowable range of values as defined by the invoked method.
 */
class ArgumentOutOfRangeException extends \InvalidArgumentException
{

}

/**
 * The exception that is thrown when a method call is invalid for the object's
 * current state, method has been invoked at an illegal or inappropriate time.
 */
class InvalidStateException extends \RuntimeException
{

}

/**
 * The exception that is thrown when a requested method or operation is not implemented.
 */
class NotImplementedException extends \LogicException
{

}

/**
 * The exception that is thrown when an invoked method is not supported. For scenarios where
 * it is sometimes possible to perform the requested operation, see InvalidStateException.
 */
class NotSupportedException extends \LogicException
{

}

/**
 * The exception that is thrown when a requested method or operation is deprecated.
 */
class DeprecatedException extends NotSupportedException
{

}

/**
 * The exception that is thrown when accessing a class member (property or method) fails.
 */
class MemberAccessException extends \LogicException
{

}

/**
 * Exception thrown if a callback refers to an undefined method or if some
 * arguments are missing.
 */
class MethodCallException extends \BadMethodCallException
{

}

/**
 * The exception that is thrown when an I/O error occurs.
 */
class IOException extends \RuntimeException
{

}

/**
 * The exception that is thrown when accessing a file that does not exist on disk.
 */
class FileNotFoundException extends IOException
{

}

/**
 * The exception that is thrown when part of a file or directory cannot be found.
 */
class DirectoryNotFoundException extends IOException
{

}

/**
 * The exception that is thrown when an argument does not match with the expected value.
 */
class InvalidArgumentException extends \InvalidArgumentException
{

}

/**
 * The exception that is thrown when an illegal index was requested.
 */
class OutOfRangeException extends \OutOfRangeException
{

}

/**
 * The exception that is thrown when a value (typically returned by function) does not match with the expected value.
 */
class UnexpectedValueException extends \UnexpectedValueException
{

}

/**
 * The exception that is thrown when static class is instantiated.
 */
class StaticClassException extends \LogicException
{

}

namespace Mesour\Components;

/**
 * Exception thrown if a callback refers to an undefined method or if some
 * arguments are missing.
 */
class MethodCallException extends \Mesour\MethodCallException
{

}

class BadRequestException extends \Exception
{

}

class NotFoundException extends \Exception
{

}
