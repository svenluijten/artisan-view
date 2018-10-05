<?php

namespace Sven\ArtisanView\Tests;

trait ViewAssertions
{
    public static function assertViewExists(string $name, string $message = ''): void
    {
        self::assertFileExists(self::normalizedPathToView($name), $message);
    }

    public static function assertViewNotExists(string $name, string $message = ''): void
    {
        self::assertFileNotExists(self::normalizedPathToView($name), $message);
    }

    private static function normalizedPathToView(string $view): string
    {
        return str_replace(['/', '\\', '.'], DIRECTORY_SEPARATOR, base_path('resources/views/'.$view));
    }
}
