<?php

namespace Controller;

class RouteController
{

    private static RouteController $instance;
    private array $routes;
    private array $redirects;

    private function __construct()
    {
        $this->routes = [];
    }

    public static function getInstance(): RouteController
    {
        if(!isset(self::$instance)) {
            self::$instance = new RouteController();
        }
        return self::$instance;
    }

    public static function addRoute(string $route, string $template, string $name): void
    {
        self::getInstance()->routes[$route] = [
            'template' => $template,
            'name' => $name
        ];
    }

    public static function addRedirect(string $from, string $to): void
    {
        self::getInstance()->redirects[$from] = $to;
    }

    public static function getRoutes(): array
    {
        return self::getInstance()->routes;
    }

    public static function getRedirects(): array
    {
        return self::getInstance()->redirects;
    }

}