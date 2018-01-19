<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Voters\ExtendsParent;
use Sven\ArtisanView\Voters\SectionsInParent;
use Sven\ArtisanView\Voters\StacksFromParent;
use Sven\ArtisanView\Voters\YieldsFromParent;
use Sven\ArtisanView\Blocks\Block;
use Symfony\Component\Console\Input\InputInterface;

class BlockStack
{
    /**
     * @var \Sven\ArtisanView\Blocks\Block[]
     */
    protected $blocks = [];

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Sven\ArtisanView\BlockStack
     */
    public function build(InputInterface $input)
    {
        $voters = [
            new ExtendsParent,
            new YieldsFromParent,
            new StacksFromParent,
            new SectionsInParent,
        ];

        /** @var \Sven\ArtisanView\Voters\Voter $voter */
        foreach ($voters as $voter) {
            if (!$voter->canHandle($input)) {
                continue;
            }

            $voter->run($input, $this);
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
