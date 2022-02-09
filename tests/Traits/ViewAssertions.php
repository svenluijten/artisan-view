<?php

namespace Sven\ArtisanView\Tests\Traits;

trait ViewAssertions
{
    /**
     * @param  string  $name
     */
    public static function assertViewExists($name)
    {
        self::assertFileExists(base_path('resources/views/'.$name));
    }

    /**
     * @param  string  $name
     */
    public static function assertViewNotExists($name)
    {
        self::assertFileDoesNotExist(base_path('resources/views/'.$name));
    }
}
