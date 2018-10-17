<?php

namespace Sven\ArtisanView\Blocks;

interface Block
{
    public function applicable(): bool;

    public function render(): string;
}
