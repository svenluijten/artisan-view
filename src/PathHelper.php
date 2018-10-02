<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Exceptions\UnsupportedException;

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
     * @param string|null $fileName
     *
     * @throws \Sven\ArtisanView\Exceptions\UnsupportedException
     *
     * @return string
     */
    public static function getPath($fileName = null)
    {
        return self::normalizePath($fileName);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function normalizePath($path)
    {
        $withoutBackslashes = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return str_replace('/', DIRECTORY_SEPARATOR, $withoutBackslashes);
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
