<?php

use JetBrains\PhpStorm\NoReturn;

function dump(mixed $args): void
{
    xdebug_var_dump($args);
}

#[NoReturn] function dd(mixed $args): void
{
    dump($args);
    die();
}