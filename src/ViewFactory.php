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
     * @var  array
     */
    protected $latest = [];

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
     *
     * @return  \Sven\ArtisanView\ViewFactory  This ViewFactory instance.
     */
    public function create($name, $extension = '.blade.php')
    {
        $filename = $this->normalizeName($name, $extension);

        $this->filesystem->write($filename, '');

        $this->latest[] = $filename;

        return $this;
    }

    /**
     * Extend an template file.
     *
     * @param  string  $name  The name of the view to extend.
     *
     * @return  \Sven\ArtisanView\ViewFactory
     */
    public function extend($name)
    {
        $stub = Stub::make()->get('extend', ['view' => $name]);

        foreach ($this->latest as $file) {
            $this->filesystem->put($file, $stub);
        }

        return $this;
    }

    /**
     * Add a section to the view(s).
     *
     * @param  string|array  $name  Name(s) of section(s) to add.
     * @param  string  $content  Content of the section for inline use.
     *
     * @return  \Sven\ArtisanView\ViewFactory
     */
    public function section($name, $content = null)
    {
        $stubContents = ($content === null)
            ? Stub::make()->get('section', compact('name'))
            : Stub::make()->get('inline-section', compact('name', 'content'));

        foreach ($this->latest as $file) {
            $this->addToFile($file, $stubContents);
        }

        return $this;
    }

    /**
     * Add multiple sections to the view(s).
     *
     * @param  array  $sections  The sections to add to the view(s).
     *
     * @return  \Sven\ArtisanView\ViewFactory
     */
    public function sections(array $sections)
    {
        foreach ($sections as $section => $content) {
            is_numeric($section) ? $this->section($content) : $this->section($section, $content);
        }

        return $this;
    }

    /**
     * Normalize the filename.
     *
     * @param  string  $name  The name of the file to create.
     * @param  string  $extension  The extension of the file to create.
     *
     * @return  string  The normalized path to the file to create.
     */
    protected function normalizeName($name, $extension)
    {
        $name = str_replace('.', '/', $name);
        $extension = Str::startsWith($extension, '.') ? $extension : ".$extension";

        return sprintf('%s%s', $name, $extension);
    }

    /**
     * Append something to the given file.
     *
     * @param  string  $filename  Name of the file to append to.
     * @param  string  $contents  Contents to add to the file.
     *
     * @return  bool  Whether or not writing to the file was successful.
     */
    protected function addToFile($filename, $contents)
    {
        $oldContents = $this->filesystem->read($filename);
        $contents = $oldContents.$contents;

        return $this->filesystem->put($filename, $contents);
    }
}
