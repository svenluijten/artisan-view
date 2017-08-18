<?php

namespace Sven\ArtisanView\Blocks;

class InlineSection extends Block
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return "@section('{$this->getName()}', '{$this->getContents()}')".PHP_EOL.PHP_EOL;
    }
}
