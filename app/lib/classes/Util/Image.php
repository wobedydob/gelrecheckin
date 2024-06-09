<?php

declare(strict_types=1);

namespace Util;

class Image
{
    public const string IMAGE_LOCATION = 'assets/img/';

    public static function new(): Image
    {
        return new self();
    }

    /**
     * Fetches the content of a given image file.
     *
     * @param string $filename name of the image file to be fetched
     * @param string $location The directory location of the image file. Default is self::image_LOCATION.
     *
     * @return string|null the content of the image file if found, or null if not found
     */
    public function get(string $filename, string $location = self::IMAGE_LOCATION): ?string
    {
        $imagePath = self::locate($filename, $location);
        return $imagePath ?: null;
    }

    /**
     * Locates a given image file.
     *
     * @param string $filename the name of the image file to be located
     * @param string $location The directory location of the image file. Default is self::image_LOCATION.
     *
     * @return string|null the absolute path of the image file if found, or null if not found
     */
    private function locate(string $filename, string $location = self::IMAGE_LOCATION): ?string
    {
        $imagePath = site_url($location) . $filename;
        return $imagePath ?: null;
    }
}
