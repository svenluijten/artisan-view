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
            return $name.$this->config->getExtension();
        }, $names);
    }

    /**
     * @param iterable                         $names
     * @param \Sven\ArtisanView\Blocks\Block[] $blocks
     */
    protected function makeViews(iterable $names, array $blocks)
    {
        $path = $this->getPath();
        $contents = BlockBuilder::build($blocks);

        foreach ($names as $name) {
            file_put_contents($path . '/' . $name, $contents);
        }
    }

    /**
     * @throws \Sven\ArtisanView\Exceptions\UnsupportedException
     * @return array
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

        return reset($paths);
    }
}
