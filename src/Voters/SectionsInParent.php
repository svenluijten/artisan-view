<?php

namespace Sven\ArtisanView\Voters;

use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Blocks\Section;
use Sven\ArtisanView\BlockStack;
use Symfony\Component\Console\Input\InputInterface;

class SectionsInParent implements Voter
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(InputInterface $input)
    {
        return $input->hasOption('section');
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, BlockStack $blockStack)
    {
        foreach ((array) $input->getOption('section') as $section) {
            if (str_contains($section, ':')) {
                list($name, $title) = explode(':', $section);

                $blockStack->add(new InlineSection($name, $title));
            } else {
                $blockStack->add(new Section($section));
            }
        }
    }
}
