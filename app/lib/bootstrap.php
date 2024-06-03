<?php

function include_php_files(string $folder, string $directory = __DIR__): void
{
    $folder = realpath($directory . DIRECTORY_SEPARATOR . $folder);

    foreach (glob("{$folder}/*.php") as $filename) {
        include_once $filename;
    }
}


include_php_files('classes');
include_php_files('../routes/');