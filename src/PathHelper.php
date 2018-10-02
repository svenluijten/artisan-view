<?php

namespace Sven\ArtisanView;

class PathHelper
{
    /**
     * @param string $path
     */
    public static function createIntermediateFolders($path)
    {
        $folderPath = static::removeFileName($path);

        if (! is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function normalizePath($path)
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function removeFileName($path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, static::normalizePath($path));

        array_pop($parts);

        return implode(DIRECTORY_SEPARATOR, $parts);
    }
}
