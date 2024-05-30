<?php

namespace Service;

use JetBrains\PhpStorm\NoReturn;

class Redirect
{

    #[NoReturn] public static function to(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

}