<?php

declare(strict_types=1);

namespace Exceptions;

use Throwable;

class DuplicateKeyException extends \Exception
{

    public function __construct(
        string $key,
        string $column,
        string $table,
        int $code = 0,
        string $message = 'Duplicate key "%s" for column "%s" in table "%s".',
        Throwable $previous = null
    )
    {
        parent::__construct(sprintf($message, $key, $column, $table), $code, $previous);
    }
}
