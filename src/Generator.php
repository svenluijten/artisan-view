<?php

namespace Sven\ArtisanView;

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
            return $name.$this->config->getExtension();
        }, $names);
    }

    /**
     * @param iterable                         $names
     * @param \Sven\ArtisanView\Blocks\Block[] $blocks
     */
    protected function makeViews(iterable $names, array $blocks)
    {
        // @todo
    }
}
