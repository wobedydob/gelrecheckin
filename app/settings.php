<?php require_once __DIR__ . '/config.php';
/* @var bool $display_errors */
/* @var string $db_host */
/* @var string $db_name */
/* @var string $db_user */
/* @var string $db_password */

ini_set('memory_limit', '1000M');

// region PHP Errors
if ($display_errors) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}

if (PHP_VERSION < '8.0') {
    throw new \RuntimeException('"This application" does not support PHP version: ' . PHP_VERSION);
}
// endregion

// region Globals
$scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
define('SITE_URL', $scheme . '://' . $name);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
// endregion

//region Database
define('DB_HOST', $db_host);
define('DB_NAME', $db_name);
define('DB_USERNAME', $db_user);
define('DB_PASSWORD', $db_password);
define('DB_PORT', 3306);
define('DB_CHAR', 'utf8');
//endregion