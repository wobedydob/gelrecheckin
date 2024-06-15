<?php

namespace Util;

class Text
{
    /**
     * Sanitizes a string by converting special characters to HTML entities and escaping others.
     *
     * @param string $input The input string to sanitize.
     * @return string The sanitized string.
     */
    public static function sanitize(string $input): string
    {
        $output = htmlspecialchars($input);
        return self::escape($output);
    }

    /**
     * Escapes certain characters in a string to prevent XSS attacks.
     *
     * @param string $input The input string to escape.
     * @return string The escaped string.
     */
    public static function escape(string $input): string
    {
        $output = '';

        for ($i = 0; $i < strlen($input); $i++) {
            $character = $input[$i];
            $ord = ord($character);

            if ($character !== "'" && $character !== "\"" && $character !== '\\' && $ord >= 32 && $ord <= 126) {
                $output .= $character;
            } else {
                $output .= '\\x' . dechex($ord);
            }
        }

        return $output;
    }

    /**
     * Hashes a password using PHP's password_hash function.
     *
     * @param string $password The password to hash.
     * @return string The hashed password.
     */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Converts an array into a string representation using a specified separator.
     *
     * @param array $array The input array to convert.
     * @param string $separator The separator used between array elements. Defaults to ', '.
     * @return string The string representation of the array.
     */
    public static function arrayToString(array $array, string $separator = ', '): string
    {
        return implode($separator, $array);
    }

    /**
     * Converts a date string into a formatted datetime string.
     *
     * @param string $date The date string to convert.
     * @return string The formatted datetime string ('Y-m-d H:i:s').
     */
    public static function toDateTime(string $date): string
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }

    /**
     * Strips HTML and PHP tags from a string.
     *
     * @param string $input The input string containing HTML and PHP tags.
     * @return string The string with tags stripped.
     */
    public static function strip(string $input): string
    {
        return strip_tags($input);
    }

    /**
     * Retrieves an excerpt from a string up to a specified limit of characters.
     *
     * @param string $input The input string to excerpt.
     * @param int $limit The maximum number of characters for the excerpt.
     * @return string The excerpted string.
     */
    public static function excerpt(string $input, int $limit): string
    {
        return substr($input, 0, $limit);
    }
}
