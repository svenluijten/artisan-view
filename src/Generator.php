<?php

namespace Sven\ArtisanView;

class Generator
{
    /**
     * @var \Sven\ArtisanView\Config
     */
    protected $config;

    /**
     * @var iterable
     */
    protected $blocks = [];

    /**
     * Generator constructor.
     *
     * @param \Sven\ArtisanView\Config $config
     * @param iterable                 $blocks
     */
    public function __construct(Config $config, iterable $blocks = [])
    {
        $this->config = $config;
        $this->blocks = $blocks;
    }

    /**
     *
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
            return $name . $this->config->getExtension();
        }, $names);
    }

    /**
     * @param iterable $names
     * @param iterable $blocks
     */
    protected function makeViews(iterable $names, iterable $blocks)
    {
        // @todo
    }
}
