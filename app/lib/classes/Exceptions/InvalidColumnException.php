<?php

declare(strict_types=1);

namespace Exceptions;

use Throwable;

class InvalidColumnException extends \Exception
{

    public function __construct(
        string $column,
        string $table,
        int $code = 0,
        string $message = 'Invalid column name "%s" given for table "%s".',
        Throwable $previous = null
    )
    {
        parent::__construct(sprintf($message, $column, $table), $code, $previous);
    }
}
