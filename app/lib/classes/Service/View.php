<?php

declare(strict_types=1);

namespace Service;

use Exception;
use Exceptions\InvalidTemplateException;

class View
{
    /** Renders the given file and ensures that the variables inside the template are in an isolated scope.
     * @throws Exception
     */
    public static function render(string $template, array $args = []): void
    {
        $path = self::getTemplatePath($template);

//        if (empty($path)) {
//            // I cant throw this exception in Docker... and cant resolve why...
//             throw new InvalidTemplateException($path, 1717407548255);
//        }

        if (!empty($args)) {
            extract($args);
        }

        include $path;
    }


    /** Returns the value of the template file.
     * @throws Exception
     */
    public static function get(string $template, array $args = []): string
    {
        ob_start();
        self::render($template, $args);
        return ob_get_clean();
    }

    /** Validates the given template. */
    public static function validate(string $template): bool
    {
        $path = self::getTemplatePath($template);
        return !empty($path);
    }

    /** Get the absolute path of the template file */
    private static function getTemplatePath(string $template): ?string
    {
        $root = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR;
        $path = $root . $template;

        if (!file_exists($path)) {
            throw new InvalidTemplateException($path, 1717407548255);
        }

        return $path;
    }
}