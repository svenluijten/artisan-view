<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Str;
use League\Flysystem\Filesystem;

class ViewFactory
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * Instantiate the ViewFacory class.
     *
     * @param \League\Flysystem\Filesystem  $filesystem  A Filesystem implementation.
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Create a new file.
     *
     * @param  string  $name  The name of the file to create.
     * @param  string  $extension  The extension of the file to create.
     * @return  \Sven\ArtisanView\ViewFactory  This ViewFactory instance.
     */
    public function create($name, $extension = '.blade.php')
    {
        $filename = $this->normalizeName($name, $extension);

        $this->filesystem->write($filename, '');

        return $this;
    }

    /**
     * Normalize the filename.
     *
     * @param  string  $name  The name of the file to create.
     * @param  string  $extension  The extension of the file to create.
     * @return  string  The normalized path to the file to create.
     */
    protected function normalizeName($name, $extension)
    {
        $name = str_replace('.', '/', $name);
        $extension = Str::startsWith($extension, '.') ? $extension : ".$extension";

        return "$name$extension";
    }
}
