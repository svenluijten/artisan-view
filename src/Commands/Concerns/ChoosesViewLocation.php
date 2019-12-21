<?php

namespace Sven\ArtisanView\Commands\Concerns;

use InvalidArgumentException;

trait ChoosesViewLocation
{
    protected function path(): string
    {
        $paths = $this->possibleLocations();

        if (count($paths) === 0) {
            throw new InvalidArgumentException($this->exceptionMessage());
        }

        if (count($paths) === 1) {
            return head($paths);
        }

        return $this->choice($this->pathQuestion(), $paths, head($paths));
    }

    abstract protected function pathQuestion(): string;

    abstract protected function possibleLocations(): array;

    abstract protected function exceptionMessage(): string;
}
