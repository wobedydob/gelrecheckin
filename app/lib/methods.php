<?php declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use Service\Route;

include_once 'debug.php';

function site_url(string $path = null): string
{
    $url = SITE_URL ?? null;
    if(!defined('SITE_URL') && $url === null) {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_SCHEME) . '://' . $_SERVER['HTTP_HOST'];
    }

    if ($path) {
        $url .= '/' . $path;
    }

    return $url;
}

function root(string $path): string
{
    $root = ROOT ?? null;
    if(!defined('ROOT') && $root === null) {
        $root = $_SERVER['DOCUMENT_ROOT'];
    }

    if ($path) {
        $root .= '/' . $path;
    }

    return $root;
}

function auth(): \Service\Auth
{
    return new \Service\Auth();
}

function render_content(): void
{
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    Route::resolve($method, $path);
}

function session(): \Service\Session
{
    return \Service\Session::instance();
}

function view(): \Service\View
{
    return new \Service\View();
}

function page(): \Service\Page
{
    return new \Service\Page();
}