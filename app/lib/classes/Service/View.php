<?php

declare(strict_types=1);

namespace Service;

use Exception;

class View
{
    /** Renders the given file and ensures that the variables inside the template are in an isolated scope.
     * @throws Exception
     */
    public static function render(string $template, array $args = []): void
    {
        $path = self::getTemplatePath($template);

        if (empty($path)) {
            throw new Exception(sprintf('Unable to load template "%s"', $template));
        }

        //convert array to usable variables for in the template
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
            return null;
        }

        return $path;
    }
}
