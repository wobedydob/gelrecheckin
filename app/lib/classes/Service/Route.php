<?php

namespace Service;

class Route
{
    private static array $routes = [];
    private static array $redirects = [];
    private static array $currentMiddlewares = [];
    private static string $currentGroupPrefix = '';

    public static function get(string $uri, $action): self
    {
        return self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, $action): self
    {
        return self::addRoute('POST', $uri, $action);
    }

    private static function addRoute(string $method, string $uri, $action): self
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

    public static function auth(array $roles = []): self
    {
        self::middleware(function () use ($roles) {
            if (!self::isAuthenticated()) {
                self::notAuthorized();
            }

            if (!empty($roles) && !self::hasRole($roles)) {
                self::notAllowed();
            }
        });

        return new self;
    }

    public static function guest(): self
    {
        self::middleware(function () {
            if (self::isAuthenticated()) {
                self::notAllowed();
            }
        });

        return new self;
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

    public static function middleware($middleware): self
    {
        $lastRouteKey = array_key_last(self::$routes);
        self::$routes[$lastRouteKey]['middlewares'][] = $middleware;
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

    private static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    private static function hasRole(array $roles): bool
    {
        return self::isAuthenticated() && in_array($_SESSION['user']['role'], $roles);
    }

    public static function resolve(string $method, string $uri): void
    {
        foreach (self::$routes as $route) {
            $pattern = "#^" . preg_replace('#\{[^\}]+\}#', '([^/]+)', $route['uri']) . "$#";
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove the full match

                foreach ($route['middlewares'] as $middleware) {
                    if (is_callable($middleware)) {
                        call_user_func($middleware);
                    } elseif (!is_array($middleware) && !call_user_func([new $middleware, 'handle'])) {
                        return;
                    }
                }

                try {
                    if (is_array($route['action'])) {
                        call_user_func_array([new $route['action'][0], $route['action'][1]], $matches);
                    } else {
                        call_user_func_array($route['action'], $matches);
                    }
                } catch (\Exception $e) {
                    \Service\ErrorHandler::throw($e);
                }

                return;
            }
        }

        // Redirects handling
        foreach (self::$redirects as $from => $to) {
            if (preg_match("#^{$from}$#", $uri)) {
                header("Location: $to", true, 302);
                exit();
            }
        }

        self::notFound();
    }

    public static function notFound(): void
    {
        header('HTTP/1.1 404 Not Found');
        View::new()->render('views/templates/404.php');
    }

    public static function notAuthorized(): void
    {
        header('HTTP/1.1 401 Not Authorized');
        Redirect::to('/');
    }

    public static function notAllowed(): void
    {
        header('HTTP/1.1 403 Forbidden');
        Redirect::to('/');
    }
}
