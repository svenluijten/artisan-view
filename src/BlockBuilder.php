<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Blocks\Block;

class BlockBuilder
{
    /**
     * @param \Sven\ArtisanView\Blocks\Block[] $blocks
     *
     * @return string
     */
    public static function build($blocks)
    {
        return array_reduce($blocks, function ($carry, Block $block) {
            return $carry.$block->render();
        }, '');
    }
}
