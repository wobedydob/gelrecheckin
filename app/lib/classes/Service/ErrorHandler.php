<?php

namespace Service;

class ErrorHandler
{

    public static function throw(\Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()} {$e->getCode()}";
        die();
    }
}