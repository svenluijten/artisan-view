<?php

namespace Sven\ArtisanView\Blocks;

class Extend implements Block
{
    /**
     * @var ?string
     */
    protected $extends;

    public function __construct(?string $extends = null)
    {
        $this->extends = $extends;
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
