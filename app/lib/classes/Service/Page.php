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

    /**
     * Retrieves a specific parameter value from the URL parameters.
     *
     * @param string $key The key of the parameter.
     * @param mixed $default The default value if the parameter is not found.
     * @return mixed|null The value of the parameter or the default value if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Sets a parameter value in the URL parameters.
     *
     * @param string $key The key of the parameter.
     * @param mixed $value The value to set for the parameter.
     */
    public function set(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * Updates multiple URL parameters with the given array.
     *
     * @param array $params An associative array of parameters to update.
     * @return string The URL with updated parameters.
     */
    public function updateUrlParams(array $params): string
    {
        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }

        return $this->urlWithParams();
    }

    /**
     * Retrieves the current URL of the page.
     *
     * @return string The current URL of the page (without parameters).
     */
    public function url(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $protocol . $host . $path;
    }

    /**
     * Retrieves the current URL of the page with all current parameters.
     *
     * @return string The current URL of the page with all current parameters.
     */
    public function urlWithParams(): string
    {
        return $this->url() . '?' . http_build_query($this->params);
    }
}