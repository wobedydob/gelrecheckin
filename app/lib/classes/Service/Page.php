<?php

namespace Service;

class Page
{

    public static function new(): Page
    {
        return new self();
    }

    public function get(string $key, $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function set(string $key, $value): mixed
    {
        return $_GET[$key] = $value;
    }

    public function url(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $protocol . $host . $path;
    }

}