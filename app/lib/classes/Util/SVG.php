<?php

declare(strict_types=1);

namespace Util;

class SVG
{
    private const string SVG_LOCATION = '/assets/img/svg/';

    public function new(): SVG
    {
        return new self();
    }

    /**
     * Fetches the content of a given SVG file.
     *
     * @param string $filename name of the SVG file to be fetched
     * @param string $location The directory location of the SVG file. Default is self::SVG_LOCATION.
     *
     * @return string|null the content of the SVG file if found, or null if not found
     */
    public function get(string $filename, string $location = self::SVG_LOCATION): ?string
    {
        $svgPath = $this->locate($filename, $location);
        return $svgPath ? file_get_contents($svgPath) : null;
    }

    /**
     * Echos the content of a given SVG file immediately.
     *
     * @param string $filename name of the SVG file to be displayed
     * @param string $location The directory location of the SVG file. Default is self::SVG_LOCATION.
     */
    public function show(string $filename, string $location = self::SVG_LOCATION): void
    {
        echo $this->get($filename, $location);
    }

    /**
     * Locates a given SVG file.
     *
     * @param string $filename the name of the SVG file to be located
     * @param string $location The directory location of the SVG file. Default is self::SVG_LOCATION.
     *
     * @return string|null the absolute path of the SVG file if found, or null if not found
     */
    public function locate(string $filename, string $location = self::SVG_LOCATION): ?string
    {
        $svgPath = root($location) . DIRECTORY_SEPARATOR . $filename;
        return file_exists($svgPath) ? $svgPath : null;
    }
}
