<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\View;
use Sven\ArtisanView\ArtisanViewServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class ViewTestCase extends AbstractPackageTestCase
{
    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return ArtisanViewServiceProvider::class;
    }

    /**
     * @param bool $force Force the creation if file already exists.
     *
     * @return View
     */
    public function view($force = false)
    {
        return new View(__DIR__.'/assets', $force);
    }

    /**
     * Set up the testing suite.
     */
    public function setUp()
    {
        mkdir(__DIR__.'/assets');
    }

    /**
     * Tear down the testing suite.
     */
    public function tearDown()
    {
        $directory = realpath(__DIR__.'/assets');

        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }

        rmdir($directory);
    }
}
