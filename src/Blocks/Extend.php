<?php

namespace Sven\ArtisanView\Blocks;

use Sven\ArtisanView\Config;

class Extend implements Block
{
    /**
     * @var string
     */
    protected $extends;

    public function __construct(Config $config)
    {
        $this->extends = $config->getExtends();
    }

    public function applicable(): bool
    {
        return $this->extends !== null;
    }

    public function render(): string
    {
        return "@extends('$this->extends')".PHP_EOL.PHP_EOL;
    }
}
