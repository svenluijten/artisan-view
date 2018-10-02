<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Blocks\Block;
use Sven\ArtisanView\Voters\ExtendsParent;
use Sven\ArtisanView\Voters\SectionsInParent;
use Sven\ArtisanView\Voters\StacksFromParent;
use Sven\ArtisanView\Voters\YieldsFromParent;
use Symfony\Component\Console\Input\InputInterface;

class BlockStack
{
    /**
     * @var \Sven\ArtisanView\Blocks\Block[]
     */
    protected $blocks = [];

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $path
     *
     * @return \Sven\ArtisanView\BlockStack
     */
    public function build(InputInterface $input, $path)
    {
        $voters = [
            new ExtendsParent,
            new YieldsFromParent,
            new StacksFromParent,
            new SectionsInParent,
        ];

        /** @var \Sven\ArtisanView\Voters\Voter $voter */
        foreach ($voters as $voter) {
            if (! $voter->canHandle($input)) {
                continue;
            }

            $voter->inPath($path)->run($input, $this);
        }

        return $this;
    }

    /**
     * @param \Sven\ArtisanView\Blocks\Block[] ...$blocks
     *
     * @return \Sven\ArtisanView\BlockStack
     */
    public function add(Block ...$blocks)
    {
        foreach ($blocks as $block) {
            $this->blocks[] = $block;
        }

        return $this;
    }

    /**
     * @return \Sven\ArtisanView\Blocks\Block[]
     */
    public function all()
    {
        return $this->blocks;
    }
}
