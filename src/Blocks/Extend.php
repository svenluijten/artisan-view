<?php

namespace Sven\ArtisanView\Blocks;

class Extend extends Block
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return "@extends('{$this->getName()}')".PHP_EOL.PHP_EOL;
    }
}
