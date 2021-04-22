<?php

namespace Sven\ArtisanView\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Sven\ArtisanView\ServiceProvider;

abstract class TestCase extends AbstractPackageTestCase
{
    protected function getServiceProviderClass()
    {
        return ServiceProvider::class;
    }

    protected function getEnvironmentSetUp($app)
    {
        @mkdir(__DIR__.'/resources/views', 0755, true);

        $app->config->set('view.paths', [
            __DIR__.'/resources/views',
        ]);

        parent::getEnvironmentSetUp($app);
    }

    protected function tearDown(): void
    {
        // Remove all the files and folders in the configured
        // view paths. This will make sure we have a clean
        // state to start the next test with.
        foreach (app('view.finder')->getPaths() as $path) {
            $this->clearDirectory($path);
        }

        parent::tearDown();
    }

    private function clearDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach ((array) scandir($path, SCANDIR_SORT_NONE) as $object) {
            if (in_array($object, ['..', '.', '.gitkeep'], false)) {
                continue;
            }

            if (is_dir($path.DIRECTORY_SEPARATOR.$object)) {
                $this->clearDirectory($path.DIRECTORY_SEPARATOR.$object);
                rmdir($path.DIRECTORY_SEPARATOR.$object);
            } else {
                unlink($path.DIRECTORY_SEPARATOR.$object);
            }
        }
    }
}
