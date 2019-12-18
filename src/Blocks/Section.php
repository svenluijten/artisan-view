<?php

namespace Sven\ArtisanView\Blocks;

use Illuminate\Support\Str;

class Section implements Block
{
    /**
     * @var string
     */
    protected $contents;

    public function __construct(string $contents = '')
    {
        $this->contents = $contents;
    }

    public function applicable(): bool
    {
        return !Str::contains($this->contents, ':');
    }

    public function render(): string
    {
        return "@section('$this->contents')".PHP_EOL.PHP_EOL.'@endsection';
    }
}
