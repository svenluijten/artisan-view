<?php

namespace Sven\ArtisanView;

class Generator extends ViewActor
{
    /**
     * Generate the view(s) as specified in the constructor.
     *
     * @param array $blocks
     */
    public function generate(array $blocks = [])
    {
        $views = $this->getViews();

        $this->makeViews(
            $this->getViewNames($views), $blocks
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
            $path = PathHelper::getPath($name);

            PathHelper::createIntermediateFolders($path);

            file_put_contents($path, $contents);
        }
    }
}
