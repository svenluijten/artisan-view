<?php

namespace Sven\ArtisanView\Exceptions;

use InvalidArgumentException;

class UnsupportedException extends InvalidArgumentException
{
    /**
     * @param int $count
     *
     * @return \Sven\ArtisanView\Exceptions\UnsupportedException
     */
    public static function tooManyPaths($count)
    {
        return new self(
            sprintf('%d paths configured in "views.paths", only one is supported.', $count)
        );
    }
}
