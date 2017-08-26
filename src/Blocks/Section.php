<?php

namespace Sven\ArtisanView\Blocks;

class Section extends Block
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return "@section('{$this->getName()}')".PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL;
    }
}
