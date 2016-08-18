<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use Illuminate\Support\Collection;
use League\Flysystem\Adapter\Local;

class ViewFactory
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var  \Illuminate\Support\Collection
     */
    protected $latest;

    /**
     * Instantiate the ViewFacory class.
     *
     * @param \League\Flysystem\Filesystem  $filesystem  A Filesystem implementation.
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->latest = new Collection;
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

        $this->latest->push($filename);

        return $this;
    }

    /**
     * Extend an template file.
     *
     * @param  string  $name  The name of the view to extend.
     * @return  \Sven\ArtisanView\ViewFactory
     */
    public function extend($name)
    {
        $this->latest->each(function($filename, $_) use ($name) {
            $variables = new Collection([
                'view' => $name
            ]);

            $this->filesystem->put(
                $filename,
                Stub::make()->get('extend', $variables)
            );
        });

        return $this;
    }

    /**
     * Add a section to the view(s).
     *
     * @param  string  $section  Name of section to add.
     * @return  \Sven\ArtisanView\ViewFactory
     */
    public function section($section)
    {
        $this->latest->each(function ($filename, $_) use ($section) {
            $variables = new Collection([
                'name' => $section,
            ]);

            $this->filesystem->put(
                $filename,
                Stub::make()->get('section', $variables)
            );
        });

        return $this;
    }

    /**
     * Add the given filename to the list of recently changed files.
     *
     * @param  string  $filename  Name of the file that was updated.
     */
    protected function addToLatest($filename)
    {
        $this->latest[] = $filename;
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
