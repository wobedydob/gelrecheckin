<?php

namespace Service;

class Route
{
    private static array $routes = [];
    private static array $redirects = [];
    private static array $currentMiddlewares = [];
    private static string $currentGroupPrefix = '';
    private static $authCallback = null;

    public static function get(string $uri, $action): self
    {
        return self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, $action): self
    {
        return self::addRoute('POST', $uri, $action);
    }

    public static function addRoute(string $method, string $uri, $action): self
    {
        $uri = self::$currentGroupPrefix . $uri;
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middlewares' => self::$currentMiddlewares,
        ];
        self::$currentMiddlewares = [];
        return new self;
    }

    public static function auth(callable $authCallback): void
    {
        self::$authCallback = $authCallback;
    }

    public static function getAuthCallback(): ?callable
    {
        return self::$authCallback;
    }

    public static function group(string $prefix, callable $routes): void
    {
        $previousGroupPrefix = self::$currentGroupPrefix;
        self::$currentGroupPrefix = $previousGroupPrefix . $prefix;
        $routes();
        self::$currentGroupPrefix = $previousGroupPrefix;
    }

    public static function name(string $name): self
    {
        $lastRouteKey = array_key_last(self::$routes);
        self::$routes[$lastRouteKey]['name'] = $name;
        return new self;
    }

    public static function middleware(string $middleware): self
    {
        self::$currentMiddlewares[] = $middleware;
        return new self;
    }

    public static function addRedirect(string $from, string $to): void
    {
        self::$redirects[$from] = $to;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRedirects(): array
    {
        return self::$redirects;
    }

    public static function resolve(string $method, string $uri): void
    {
        foreach (self::$routes as $route) {

            if ($route['method'] === $method && preg_match("#^{$route['uri']}$#", $uri)) {


                foreach ($route['middlewares'] as $middleware) {

                    if (!call_user_func([new $middleware, 'handle'])) {
                        return;
                    }

                }

                if (isset(self::$authCallback) && !call_user_func(self::$authCallback)) {

                    header("HTTP/1.1 401 Unauthorized");
                    echo "401 Unauthorized";
                    return;

                }

                if (is_array($route['action'])) {

                    call_user_func([new $route['action'][0], $route['action'][1]]);

                } else {

                    call_user_func($route['action']);

                }

                return;
            }

        }

        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}
