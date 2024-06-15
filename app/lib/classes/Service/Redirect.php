<?php

namespace Service;

use JetBrains\PhpStorm\NoReturn;

class Redirect
{

    /**
     * Redirects to the specified URL.
     *
     * @param string $url The URL to redirect to.
     * @return void
     */
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