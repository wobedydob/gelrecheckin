<?php declare(strict_types=1);

spl_autoload_register(function ($classname) {
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    $file = __DIR__ . DIRECTORY_SEPARATOR . $classname . '.php';

    if (file_exists($file)) {
        include $file;
    }

});
