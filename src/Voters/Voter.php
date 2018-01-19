<?php

namespace Sven\ArtisanView\Voters;

use Sven\ArtisanView\BlockStack;
use Symfony\Component\Console\Input\InputInterface;

interface Voter
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    public function canHandle(InputInterface $input);

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Sven\ArtisanView\BlockStack $blockStack
     *
     * @return void
     */
    public function run(InputInterface $input, BlockStack $blockStack);
}
