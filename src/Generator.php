<?php

namespace Sven\ArtisanView;

class Generator extends ViewActor
{
    /**
     * @param \Sven\ArtisanView\BlockStack $blockStack
     */
    public function generate(BlockStack $blockStack)
    {
        $views = $this->getViews();

        $this->makeViews(
            $this->getViewNames($views), $blockStack->all()
        );
    }

    /**
     * @param array                            $names
     * @param \Sven\ArtisanView\Blocks\Block[] $blocks
     */
    protected function makeViews(array $names, array $blocks)
    {
        $contents = BlockBuilder::build($blocks);

        foreach ($names as $name) {
            $path = PathHelper::normalizePath($this->config->getPath().DIRECTORY_SEPARATOR.$name);
            PathHelper::createIntermediateFolders($path);

            file_put_contents($path, $contents);
        }
    }
}
