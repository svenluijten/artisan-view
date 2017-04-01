<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Str;
use League\Flysystem\Filesystem;

class ViewGenerator
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Instantiate the ViewFacory class.
     *
     * @param \League\Flysystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function make(string $name, string $extension = 'blade.php')
    {
        $this->parts = $this->makeParts($name, $extension);

        return $this;
    }

    /**
     * @param string $name
     * @param string $extension
     *
     * @return string
     */
    protected function makeParts(string $name, string $extension)
    {
        $name = $this->parseName($name);
        $extension = $this->parseExtension($extension);

        return $name.$extension;
    }

    /**
     * @param string $extension
     *
     * @return string
     */
    protected function parseExtension(string $extension)
    {
        return Str::startsWith($extension, '.') ? $extension : ".$extension";
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function parseName(string $name)
    {
        $path = explode('.', $name);

        return resource_path('views/'.$path);
    }
}
