<?php

namespace Service;

class Page
{
    private array $params;

    public function __construct()
    {
        $this->params = $_GET;
    }

    public static function new(): Page
    {
        return new self();
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function updateUrlParams(array $params): string
    {
        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }

        return $this->urlWithParams();
    }

    public function url(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $protocol . $host . $path;
    }

    public function urlWithParams(): string
    {
        return $this->url() . '?' . http_build_query($this->params);
    }
}