<?php

namespace Service;

use JetBrains\PhpStorm\NoReturn;

class Route
{
    private static array $routes = [];
    private static array $redirects = [];
    private static array $currentMiddlewares = [];
    private static string $currentGroupPrefix = '';

    /**
     * Registers a route for HTTP GET method.
     *
     * @param string $uri The URI path pattern.
     * @param mixed $action The callback function or controller method to execute.
     * @return self Returns an instance of the Route class.
     */
    public static function get(string $uri, mixed $action): self
    {
        return self::addRoute('GET', $uri, $action);
    }

    /**
     * Registers a route for HTTP POST method.
     *
     * @param string $uri The URI path pattern.
     * @param mixed $action The callback function or controller method to execute.
     * @return self Returns an instance of the Route class.
     */
    public static function post(string $uri, mixed $action): self
    {
        return self::addRoute('POST', $uri, $action);
    }

    /**
     * Adds a route to the internal routes array.
     *
     * @param string $method The HTTP method of the route.
     * @param string $uri The URI path pattern.
     * @param mixed $action The callback function or controller method to execute.
     * @return self Returns an instance of the Route class.
     */
    private static function addRoute(string $method, string $uri, mixed $action): self
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

    /**
     * Registers an 'auth' middleware to enforce authentication and role-based access.
     *
     * @param array $roles Optional. Array of roles allowed to access the route.
     * @return self Returns an instance of the Route class.
     */
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

    /**
     * Registers a 'guest' middleware to allow access only to non-authenticated users.
     *
     * @return self Returns an instance of the Route class.
     */
    public static function guest(): self
    {
        self::middleware(function () {
            if (self::isAuthenticated()) {
                self::notAllowed();
            }
        });

        return new self;
    }

    /**
     * Defines a group of routes with a common prefix and shared middleware.
     *
     * @param string $prefix The prefix for the group of routes.
     * @param callable $routes A callable function that defines the routes within the group.
     */
    public static function group(string $prefix, callable $routes): void
    {
        $previousGroupPrefix = self::$currentGroupPrefix;
        self::$currentGroupPrefix = $previousGroupPrefix . $prefix;
        $routes();
        self::$currentGroupPrefix = $previousGroupPrefix;
    }

    /**
     * Assigns a name to the last registered route.
     *
     * @param string $name The name of the route.
     * @return self Returns an instance of the Route class.
     */
    public static function name(string $name): self
    {
        $lastRouteKey = array_key_last(self::$routes);
        self::$routes[$lastRouteKey]['name'] = $name;
        return new self;
    }

    /**
     * Adds a middleware to the last registered route.
     *
     * @param mixed $middleware The middleware callback function or class name.
     * @return self Returns an instance of the Route class.
     */
    public static function middleware(mixed $middleware): self
    {
        $lastRouteKey = array_key_last(self::$routes);
        self::$routes[$lastRouteKey]['middlewares'][] = $middleware;
        return new self;
    }

    /**
     * Adds a redirect from one URI to another.
     *
     * @param string $from The source URI pattern.
     * @param string $to The target URI.
     */
    public static function addRedirect(string $from, string $to): void
    {
        self::$redirects[$from] = $to;
    }

    /**
     * Retrieves all registered routes.
     *
     * @return array An array containing all registered routes.
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Retrieves all registered redirects.
     *
     * @return array An array containing all registered redirects.
     */
    public static function getRedirects(): array
    {
        return self::$redirects;
    }

    /**
     * Checks if the user is set in the session.
     *
     * @return bool True if the user is authenticated, false otherwise.
     */
    private static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if the authenticated user has the specified role.
     *
     * @param array $roles Array of roles to check against.
     * @return bool True if the user has any of the specified roles, false otherwise.
     */
    private static function hasRole(array $roles): bool
    {
        return self::isAuthenticated() && in_array($_SESSION['user']['role'], $roles);
    }

    /**
     * Resolves the route for the given HTTP method and URI.
     *
     * @param string $method The HTTP method of the request.
     * @param string $uri The URI of the request.
     */
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
                    Error::throw($e);
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

    /**
     * Retrieves the route information by its name.
     *
     * @param string $name The name of the route.
     * @return array|null The route information array if found, otherwise null.
     */
    public static function getRouteByName(string $name): ?array
    {
        return array_filter(self::$routes, function ($route) use ($name) {
            return $route['name'] === $name;
        });
    }

    /**
     * Executes the route by its name.
     *
     * @param string $name The name of the route.
     * @return array|null The route information array if found, otherwise null.
     */
    public static function executeByName(string $name): ?array
    {
        $route = self::getRouteByName($name);
        return array_shift($route);
    }

    /**
     * Handles 404 Not Found error.
     */
    public static function notFound(): void
    {
        header('HTTP/1.1 404 Not Found');
        View::new()->render('views/templates/404.php');
    }

    /**
     * Handles 401 Not Authorized error.
     */
    #[NoReturn] public static function notAuthorized(): void
    {
        header('HTTP/1.1 401 Not Authorized');
        Redirect::to('/');
    }

    /**
     * Handles 403 Forbidden error.
     */
    #[NoReturn] public static function notAllowed(): void
    {
        header('HTTP/1.1 403 Forbidden');
        Redirect::to('/');
    }
}
