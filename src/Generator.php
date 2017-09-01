<?php

namespace Sven\ArtisanView;

class Generator extends ViewActor
{
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
        $this->blocks = $blocks;

        parent::__construct($config);
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
