<?php

namespace Exceptions;

use Throwable;

class MissingPropertyException extends \Exception
{

    public function __construct(
        mixed     $property,
        mixed     $class,
        int       $code,
        string    $message = 'Missing property "%s" in class "%s" given with code',
        Throwable $previous = null
    )
    {
        parent::__construct(sprintf($message, $property, $class), $code, $previous);
    }
}
