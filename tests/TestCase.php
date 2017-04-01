<?php

namespace Sven\ArtisanView\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class TestCase extends AbstractPackageTestCase
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Sven\ArtisanView\ViewGenerator
     */
    protected $view;

    /**
     * @var string
     */
    protected $assetPath = __DIR__.'\\assets';

    /**
     * Set up the testing suite.
     */
    public function setUp()
    {
        //
    }

    /**
     * Tear down the application suite.
     */
    public function tearDown()
    {
        $directory = realpath($this->assetPath);
        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
    }
}
