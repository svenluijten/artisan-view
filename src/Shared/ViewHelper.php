<?php

namespace Sven\ArtisanView\Shared;

use Illuminate\Support\Str;
use Sven\ArtisanView\Exceptions\FileDoesNotExist;
use Sven\ArtisanView\Exceptions\FileAlreadyExists;

class ViewHelper
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $force;

    /**
     * Construct the ViewHelper.
     *
     * @param string $path  Path to the view.
     * @param bool   $force Force the creation if file already exists.
     */
    public function __construct($path, $force)
    {
        $this->path = realpath($path);
        $this->force = $force;
    }

    /**
     * Get the path for the given view.
     *
     * @param string $name Name of view.
     *
     * @return string Full path to the view.
     */
    public function getPathFor($name)
    {
        $fragments = $this->normalizeToArray($name, '.');

        $filename = array_pop($fragments);

        $this->createNestedFolders($fragments);

        return $this->addToPath($filename);
    }

    /**
     * Make folders if they don't yet exist.
     *
     * @param array $folders Folders to create.
     *
     * @return string Path to deepest nested folder.
     */
    public function createNestedFolders($folders)
    {
        if (empty($folders)) {
            return;
        }

        $path = $this->addToPath(array_shift($folders));

        if (! is_dir($path)) {
            mkdir($path);
        }

        return $this->createNestedFolders($folders);
    }

    /**
     * Add the given extension to the path.
     *
     * @param string $extension The extension to tack on.
     */
    public function addExtension($extension)
    {
        return $this->path.$this->parseExtension($extension);
    }

    /**
     * Add given folder to the path property.
     *
     * @param string $folder The folder to add to the path.
     */
    public function addToPath($folder)
    {
        $this->path .= "/$folder";

        return $this->path;
    }

    public function appendTo($path, $content)
    {
        file_put_contents($path, $content, FILE_APPEND);
    }

    /**
     * Create a file located at the given path.
     *
     * @param string $path The full path to the file.
     *
     * @throws \Sven\ArtisanView\Exceptions\FileAlreadyExists
     *
     * @return void
     */
    public function makeFile($path)
    {
        if (! $this->force && file_exists($path)) {
            throw new FileAlreadyExists("The file at [$path] already exists.");
        }

        file_put_contents($path, '');
    }

    /**
     * Remove a file located at the given path.
     *
     * @param string $path Full path to the file.
     *
     * @throws \Sven\ArtisanView\Exceptions\FileDoesNotExist
     *
     * @return void
     */
    public function removeFile($path)
    {
        if (! file_exists($path)) {
            throw new FileDoesNotExist("The file at [$path] does not exist.");
        }

        unlink($path);
    }

    /**
     * Normalize a string to an array.
     *
     * @param string|array $value     The value to normalize.
     * @param string       $delimiter Delimiter to explode by.
     *
     * @return array Normalized array of values.
     */
    public function normalizeToArray($value, $delimiter)
    {
        if (is_array($value)) {
            return $value;
        }

        if (! Str::contains($value, $delimiter)) {
            return [$value];
        }

        return explode($delimiter, $value);
    }

    /**
     * Normalize the extension so it starts with a period.
     *
     * @param string $extension The extension to normalize.
     *
     * @return string
     */
    public function parseExtension($extension)
    {
        return Str::startsWith($extension, '.') ? $extension : ".$extension";
    }

    /**
     * Set the curren path.
     *
     * @param string $path The path to set it to.
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
