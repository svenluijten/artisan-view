<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Collection;
use Sven\ArtisanView\Blocks\Block;

class BlockBuilder
{
    protected static $blocks = [
        Blocks\Extend::class,
        Blocks\Section::class,
        Blocks\InlineSection::class,
    ];

    public static function build(Config $config): string
    {
        return Collection::make(self::$blocks)
            ->map(function (string $block) use ($config) {
                return new $block($config);
            })
            ->filter(function (Block $block) {
                return $block->applicable();
            })
            ->reduce(function (string $carry, Block $block) {
                return $carry.$block->render();
            }, '');
    }
}
