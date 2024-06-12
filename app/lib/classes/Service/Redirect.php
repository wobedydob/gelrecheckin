<?php

namespace Service;

use JetBrains\PhpStorm\NoReturn;

class Redirect
{

    #[NoReturn] public static function to(string $url): void
    {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        } else {
            // Handle the case where headers have already been sent
            echo "<script>window.location.href='$url';</script>";
            exit;
        }
    }

}