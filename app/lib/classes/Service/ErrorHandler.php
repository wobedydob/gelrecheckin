<?php

namespace Service;

class ErrorHandler
{

    private static array $errors = [];

    public static function log($exception): void
    {
        self::$errors[] = $exception;
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function hasErrors(): bool
    {
        return !empty(self::$errors);
    }

    public static function throw(\Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()} {$e->getCode()}";
        die();
    }
}