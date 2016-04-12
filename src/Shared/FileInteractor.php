<?php

namespace Sven\ArtisanView\Shared;

use Illuminate\Support\Str;
use Sven\ArtisanView\Exceptions\FileDoesNotExist;
use Sven\ArtisanView\Exceptions\FileAlreadyExists;

class FileInteractor
{
    /**
     * Full path where the file to create should be located.
     *
     * @var string
     */
    protected $path;

    /**
     * Base path where your views are located.
     *
     * @var string
     */
    protected $base;

    /**
     * Full path to the file.
     *
     * @var string
     */
    protected $file;

    /**
     * Instantiate the FileInteractor.
     *
     * @param string $path Base path to files to work with.
     */
    public function __construct($path)
    {
        $realPath = realpath($path);

        $this->path = $realPath;
        $this->base = $realPath;
    }

    /**
     * Set paths back to their defaults.
     *
     * @return \Sven\ArtisanView\Shared\FileInteractor
     */
    protected function clean()
    {
        $this->path = $this->base;
        $this->file = '';

        return $this;
    }

    /**
     * Normalize a string to an array.
     *
     * @param  string|array $value     The value to normalize.
     * @param  string       $delimiter Delimiter to explode by.
     * @return array                   Normalized array of values.
     */
    protected function normalizeToArray($value, $delimiter)
    {
        if (is_array($value)) {
            return $value;
        }

        if ( ! Str::contains($value, $delimiter)) {
            return [$value];
        }

        return explode($delimiter, $value);
    }

    /**
     * Recursively create folders.
     *
     * @param  array  $folders Folders to create inside each other.
     * @return void
     */
    protected function createFolders($folders)
    {
        if (empty($folders)) {
            return;
        }

        $path = $this->addToPath(array_pop($folders));

        if ( ! is_dir($path)) {
            mkdir($path);
        }

        return $this->createFolders($folders);
    }

    /**
     * Add given folder to the path property.
     *
     * @param string $folder The folder to add to the path.
     */
    private function addToPath($folder)
    {
        $this->path .= "/$folder";

        return $this->path;
    }

    /**
     * Build the full path to the file.
     *
     * @param  string $filename  Name of the file.
     * @param  string $extension Extension of the file.
     * @return string            The full path to the file.
     */
    protected function fullPath($filename, $extension)
    {
        $extension = $this->parseExtension($extension);

        $this->file = $this->path.'/'.$filename.$extension;

        return $this->file;
    }

    /**
     * Create a file from the filename and extension.
     *
     * @param  string $filename  The name of the file.
     * @param  string $extension The extension of the file.
     * @throws \Sven\ArtisanView\Exceptions\FileAlreadyExists
     * @return void
     */
    protected function makeFile($filename, $extension)
    {
        $file = $this->fullPath($filename, $extension);

        if (file_exists($file)) {
            throw new FileAlreadyExists("The file at [$file] already exists.");
        }

        $this->appendToFile('');
    }

    /**
     * Remove a file from the filename and extension.
     *
     * @param  string $filename  The name of the file.
     * @param  string $extension The extension of the file.
     * @throws \Sven\ArtisanView\Exceptions\FileDoesNotExist
     * @return void
     */
    protected function removeFile($filename, $extension)
    {
        $file = $this->fullPath($filename, $extension);

        if ( ! file_exists($file)) {
            throw new FileDoesNotExist("The file at [$file] does not exist.");
        }

        unlink($file);
    }

    /**
     * Append to the current file.
     *
     * @param  string $content Content to append.
     * @return void
     */
    protected function appendToFile($content)
    {
        file_put_contents($this->file, $content, FILE_APPEND);
    }

    /**
     * Prepare the name into a full file path.
     *
     * @param  string|array $name The name to parse.
     * @return string             Full path to the file.
     */
    protected function parseName($name)
    {
        $fragments = $this->normalizeToArray($name, '.');

        $filename = array_pop($fragments);

        $this->createFolders($fragments);

        return $filename;
    }

    /**
     * Normalize the extension so it starts with a period.
     *
     * @param  string $extension The extension to normalize.
     * @return string
     */
    private function parseExtension($extension)
    {
        return Str::startsWith($extension, '.') ? $extension : ".$extension";
    }
}
