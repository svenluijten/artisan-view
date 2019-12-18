<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Arr;
use Sven\ArtisanView\Blocks\Block;
use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Blocks\Section;

class BlockBuilder
{
    public static function make(): self
    {
        return new self();
    }

    public function build(Config $config): string
    {
        return array_reduce($this->getBlocks($config), function (string $carry, Block $block) {
            return $carry.$block->render().PHP_EOL.PHP_EOL;
        }, '');
    }

    protected function getBlocks(Config $config): array
    {
        return array_merge(
            $this->buildExtends($config),
            $this->buildSections($config)
        );
    }

    protected function buildExtends(Config $config): array
    {
        $block = new Extend($config->getExtends());

        if ($block->applicable()) {
            return [$block];
        }

        return [];
    }

    protected function buildSections(Config $config): array
    {
        return array_map([$this, 'getSectionBlock'], $config->getSections());
    }

    protected function getSectionBlock(string $contents): Block
    {
        $section = new Section($contents);
        $inlineSection = new InlineSection($contents);

        return Arr::first([$section, $inlineSection], function (Block $block) {
            return $block->applicable();
        });
    }
}
