<?php

namespace Service;

class Error
{

    private static array $errors = [];

    public static function get(): array
    {
        return self::$errors;
    }

    public static function set(array $errors): void
    {
        self::$errors = $errors;
    }

}