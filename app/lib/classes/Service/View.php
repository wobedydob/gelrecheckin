<?php

declare(strict_types=1);

namespace Service;

use Exception;
use Exceptions\InvalidTemplateException;

class View
{

    public static function new(): View
    {
        return new self();
    }

    /**
     * Renders the specified template file with optional arguments.
     *
     * @param string $template The name or path of the template file.
     * @param array $args Optional. Associative array of variables to be extracted into the template's scope.
     * @throws Exception If the template file is not found or cannot be accessed.
     */
    public function render(string $template, array $args = []): void
    {
        $path = self::getTemplatePath($template);

        if (!empty($args)) {
            extract($args);
        }

        include $path;
    }

    /**
     * Retrieves the output of the specified template file with optional arguments.
     *
     * @param string $template The name or path of the template file.
     * @param array $args Optional. Associative array of variables to be extracted into the template's scope.
     * @return string The rendered output of the template file.
     * @throws Exception If the template file is not found or cannot be accessed.
     */
    public function get(string $template, array $args = []): string
    {
        ob_start();
        self::render($template, $args);
        return ob_get_clean();
    }

    /**
     * Validates whether the specified template exists.
     *
     * @param string $template The name or path of the template file.
     * @return bool True if the template file exists, false otherwise.
     */
    public function validate(string $template): bool
    {
        $path = self::getTemplatePath($template);
        return !empty($path);
    }

    /**
     * Retrieves the absolute path of the specified template file.
     *
     * @param string $template The name or path of the template file.
     * @return string|null The absolute path of the template file if found, null otherwise.
     * @throws InvalidTemplateException If the template file is not found.
     */
    private function getTemplatePath(string $template): ?string
    {
        $root = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR;
        $path = $root . $template;

        if (!file_exists($path)) {
            throw new InvalidTemplateException($path, 1717407548255);
        }

        return $path;
    }
}