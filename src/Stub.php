<?php

namespace Sven\ArtisanView;

use League\Flysystem\Filesystem;
use Illuminate\Support\Collection;
use League\Flysystem\Adapter\Local;

class Stub
{
    /**
     * @var  \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * Instantiate a new Stub class.
     *
     * @param  Filesystem  $filesystem  A filesystem implementation.
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Instantiate a new Stub with a pre-filled Filesystem implementation.
     *
     * @return  \Sven\ArtisanView\Stub
     */
    public static function make()
    {
        $filesystem = new Filesystem(
            new Local(__DIR__)
        );

        return new self($filesystem);
    }

    /**
     * Get the contents of a stub.
     *
     * @param  string  $stub  The name of the stub to get.
     * @param  Collection  $variables  Stub-specific variables.
     * @return  string  The contents of the stub.
     */
    public function get($stub, Collection $variables)
    {
        $contents = $this->filesystem->read('stubs/' . $stub . '.stub');

        return $this->replace($variables, $contents);
    }

    /**
     * Replace the placeholders in a stub with their new values.
     *
     * @param  Collection  $variables  Placeholders to replace & with what.
     * @param  string  $contents  Original contents from the stub.
     * @return  string  New contents for the stub.
     */
    protected function replace(Collection $variables, $contents)
    {
        $variables->each(function ($newValue, $placeholder) use (&$contents) {
            $contents = str_replace(
                sprintf('{%s}', $placeholder),
                $newValue,
                $contents
            );
        });

        return $contents;
    }
}
