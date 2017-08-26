<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Exceptions\UnsupportedException;

class Generator
{
    /**
     * @var \Sven\ArtisanView\Config
     */
    protected $config;

    /**
     * @var \Sven\ArtisanView\Blocks\Block[]
     */
    protected $blocks = [];

    /**
     * Generator constructor.
     *
     * @param \Sven\ArtisanView\Config         $config
     * @param \Sven\ArtisanView\Blocks\Block[] $blocks
     */
    public function __construct(Config $config, array $blocks = [])
    {
        $this->config = $config;
        $this->blocks = $blocks;
    }

    /**
     * Generate the view(s) as specified in the constructor.
     */
    public function generate()
    {
        $views = $this->config->isResource()
            ? $this->config->getVerbs()
            : [$this->config->getName()];

        $this->makeViews(
            $this->getViewNames($views), $this->blocks
        );
    }

    /**
     * @param iterable $names
     *
     * @return array
     */
    protected function getViewNames(iterable $names)
    {
        return array_map(function ($name) {
            $name = str_replace('.', '/', $name);

            return $name.$this->config->getExtension();
        }, $names);
    }

    /**
     * @param iterable                         $names
     * @param \Sven\ArtisanView\Blocks\Block[] $blocks
     */
    protected function makeViews(iterable $names, array $blocks)
    {
        $contents = BlockBuilder::build($blocks);

        foreach ($names as $name) {
            $fileName = $this->createIntermediateFolders(
                $this->getPath(), $this->normalizePath($name)
            );

            file_put_contents($fileName, $contents);
        }
    }

    /**
     * @param string $path
     * @param string $fileName
     *
     * @return string
     */
    protected function createIntermediateFolders($path, $fileName)
    {
        if (! str_contains($fileName, DIRECTORY_SEPARATOR)) {
            return $path.DIRECTORY_SEPARATOR.$fileName;
        }

        $folders = explode(DIRECTORY_SEPARATOR, $fileName);
        $file = array_pop($folders);
        $folders = implode(DIRECTORY_SEPARATOR, $folders);
        $fullPath = $path.DIRECTORY_SEPARATOR.$folders;

        mkdir($fullPath, 0777, true);

        return $fullPath.DIRECTORY_SEPARATOR.$file;
    }

    /**
     * @throws \Sven\ArtisanView\Exceptions\UnsupportedException
     *
     * @return string
     */
    protected function getPath()
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

        return $this->normalizePath(realpath(reset($paths)));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function normalizePath($path)
    {
        $withoutBackslashes = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return str_replace('/', DIRECTORY_SEPARATOR, $withoutBackslashes);
    }
}
