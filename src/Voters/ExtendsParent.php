<?php

namespace Sven\ArtisanView\Voters;

use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\BlockStack;
use Symfony\Component\Console\Input\InputInterface;

class ExtendsParent implements Voter
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(InputInterface $input)
    {
        return $input->hasOption('extends') && $input->getOption('extends');
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, BlockStack $blockStack)
    {
        $blockStack->add(new Extend($input->getOption('extends')));
    }
}
