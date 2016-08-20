<?php

namespace Sven\ArtisanView;

use League\Flysystem\Filesystem;
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
     * @param  array  $variables  Stub-specific variables.
     *
     * @return  string  The contents of the stub.
     */
    public function get($stub, array $variables)
    {
        $contents = $this->filesystem->read(
            sprintf('stubs/%s.stub', $stub)
        );

        return $this->replace($variables, $contents);
    }

    /**
     * Replace the placeholders in a stub with their new values.
     *
     * @param  array  $variables  Placeholders to replace & with what.
     * @param  string  $contents  Original contents from the stub.
     *
     * @return  string  New contents of the stub.
     */
    protected function replace(array $variables, $contents)
    {
        $placeholders = array_map(function ($item) {
            return '{'.$item.'}';
        }, array_keys($variables));

        $newValues = array_values($variables);

        return str_replace(
            $placeholders,
            $newValues,
            $contents
        );
    }
}
