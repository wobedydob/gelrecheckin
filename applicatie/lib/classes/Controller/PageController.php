<?php

namespace Controller;

use Service\View;
use Util\Url;

class PageController
{

    private Url $url;
    private string $template;

    public function __construct()
    {
        $url =  parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $this->url = new Url($url['scheme'], $url['host'], $url['path'], $url['query'] ?? null);

        $routes = RouteController::getRoutes();
        foreach($routes as $path => $route) {
            if($path === $this->url->path) {
                $this->template = $route['template'];
                break;
            }
        }

        $redirects = RouteController::getRedirects();
        foreach($redirects as $path => $redirect) {
            if($path === $this->url->path) {
                self::redirect($redirect);
                break;
            }
        }

        if(!isset($this->template)) {
            $this->template = 'views/templates/404.php';
        }

    }

    public function locateTemplate(): void
    {
        $file = $this->template;

        if(!file_exists($file)){
            throw new \Exception('File does not exist: ' . $file);
        }

        View::render($file);
    }

    public static function redirect(string $page): void
    {
        $location = 'Location: ' . SITE_URL . DIRECTORY_SEPARATOR . $page;

        if($page === '/') {
            $location = 'Location: ' . SITE_URL;
        }

        header($location);
    }

}