<?php

namespace Enums;

enum PDOError
{
    case DUPLICATE_KEY;
    case INVALID_COLUMN;

    public function getCode(): string
    {
        return match ($this) {
            self::DUPLICATE_KEY => '23000',
            self::INVALID_COLUMN => '42S22',
        };
    }
}
