<?php

namespace Service;

use Exception;
use JetBrains\PhpStorm\NoReturn;

class Error
{

    private static array $errors = [];

    /**
     * Retrieves all logged errors.
     *
     * @return array The array of logged errors.
     */
    public static function get(): array
    {
        return self::$errors;
    }

    /**
     * Sets the array of errors.
     *
     * @param array $errors The array of errors to set.
     */
    public static function set(array $errors): void
    {
        self::$errors = $errors;
    }

    /**
     * Logs an exception or error.
     *
     * @param mixed $error The exception or error to log.
     */
    public static function log(mixed $error): void
    {
        self::$errors[] = $error;
    }

    /**
     * Clears all logged errors.
     */
    public static function clear(): void
    {
        self::$errors = [];
    }

    /**
     * Checks if there are any logged errors.
     *
     * @return bool True if there are logged errors, false otherwise.
     */
    public static function hasErrors(): bool
    {
        return !empty(self::$errors);
    }

    /**
     * Logs and throws an exception.
     *
     * @param Exception $exception The exception to log and throw.
     */
    #[NoReturn] public static function throw(Exception $exception)
    {
        echo "An error has occurred: {$exception->getMessage()} {$exception->getCode()}";
        die();
    }

}