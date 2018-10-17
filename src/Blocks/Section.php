<?php

namespace Sven\ArtisanView\Blocks;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Sven\ArtisanView\Config;

class Section implements Block
{
    /**
     * @var array
     */
    protected $sections;

    public function __construct(Config $config)
    {
        $this->sections = $config->getSections();
    }

    public function applicable(): bool
    {
        return $this->applicableSections()->isNotEmpty();
    }

    public function render(): string
    {
        return $this->applicableSections()
            ->reduce(function (string $carry, string $section) {
                return $carry."@section('$section')".PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL;
            }, '');
    }

    protected function applicableSections(): Collection
    {
        return Collection::make($this->sections)
            ->reject(function (string $section) {
                return Str::contains($section, ':');
            });
    }
}
