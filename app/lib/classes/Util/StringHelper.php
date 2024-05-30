<?php

namespace Util;

class StringHelper
{

    /** Checks the string and sanitizes it for non-html characters. */
    public static function sanitize(string $input): string
    {
        $output = htmlspecialchars($input);
        return self::escape($output);
    }

    /** Checks if there are any other illegal symbols or characters in the input string. */
    public static function escape(string $input): string
    {

        $output = '';

        for($i = 0; $i < strlen($input); $i++){
            $character = $input[$i];
            $ord = ord($character);
            
            if($character !== "'" && $character !== "\"" && $character !== '\\' && $ord >= 32 && $ord <= 126){
                $output .= $character;
            } else {
                $output .= '\\x' . dechex($ord);
            }


        }

        return $output;
    }

    /** Hashes input string with password_hash(). */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

}