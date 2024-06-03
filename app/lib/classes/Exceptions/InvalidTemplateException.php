<?php

namespace Exceptions;

use Throwable;

class InvalidTemplateException extends \Exception
{

    public function __construct(
        mixed    $template,
        int       $code,
        string    $message = 'Invalid template location "%s" given with code',
        Throwable $previous = null
    )
    {
        parent::__construct(sprintf($message, $template), $code, $previous);
    }
}
