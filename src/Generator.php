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
     * Generate the view(s) as specified in the constructor.
     */
    public function generate()
    {
        $views = $this->getViews();

        $this->makeViews(
            $this->getViewNames($views), $this->blocks
        );
    }

    /**
     * @return array
     */
    protected function getViews()
    {
        if (! $this->config->isResource()) {
            return [$this->config->getName()];
        }

        return array_map(function ($view) {
            return $this->config->getName().'.'.$view;
        }, $this->config->getVerbs());
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
            $path = PathHelper::getPath($name);

            PathHelper::createIntermediateFolders($path);

            file_put_contents($path, $contents);
        }
    }
}
