<?php

namespace Sven\ArtisanView\Blocks;

class Push extends Block
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return "@push('{$this->getName()}')".PHP_EOL.PHP_EOL.'@endpush'.PHP_EOL.PHP_EOL;
    }
}
