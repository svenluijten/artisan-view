<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Exceptions\UnsupportedException;

class PathHelper
{
    /**
     * @param string $path
     * @param string $fileName
     *
     * @return string
     */
    public static function createIntermediateFolders($path, $fileName)
    {
        if (! str_contains($fileName, DIRECTORY_SEPARATOR)) {
            return $path.DIRECTORY_SEPARATOR.$fileName;
        }

        $folders = explode(DIRECTORY_SEPARATOR, $fileName);
        $file = array_pop($folders);
        $folders = implode(DIRECTORY_SEPARATOR, $folders);
        $fullPath = $path.DIRECTORY_SEPARATOR.$folders;

        if (! is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        return $fullPath.DIRECTORY_SEPARATOR.$file;
    }

    /**
     * @throws \Sven\ArtisanView\Exceptions\UnsupportedException
     *
     * @return string
     */
    public static function getPath()
    {
        /** @var \Illuminate\View\FileViewFinder $viewFinder */
        $viewFinder = app('view.finder');

        $paths = $viewFinder->getPaths();

        // If we have more than one path configured, throw an
        // exception as this is currently not supported by
        // the package. It might be supported later on.
        if (count($paths) !== 1) {
            throw UnsupportedException::tooManyPaths(count($paths));
        }

        return self::normalizePath(realpath(reset($paths)));
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
}
