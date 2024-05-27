<?php

// region PHP Errors
// determines if errors should be displayed
$errors = true;

if ($errors) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}

if (PHP_VERSION < '8.0') {
    throw new \Exception('"This application" does not support PHP version: ' . PHP_VERSION);
}
// endregion

// region Globals
$scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
define('SITE_URL', $scheme . '://' . $name);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
// endregion