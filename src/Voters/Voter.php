<?php

namespace Sven\ArtisanView\Voters;

use Sven\ArtisanView\BlockStack;
use Symfony\Component\Console\Input\InputInterface;

interface Voter
{
    /**
     * See if the class needs to be used with the current input.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    public function canHandle(InputInterface $input);

    /**
     * Add a section to the given block stack.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Sven\ArtisanView\BlockStack $blockStack
     *
     * @return void
     */
    public function run(InputInterface $input, BlockStack $blockStack);
}
