<?php

namespace Sven\ArtisanView\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Illuminate\View\Factory;
use Sven\ArtisanView\ServiceProvider;

abstract class TestCase extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    /**
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->view = $this->app->make(Factory::class);
    }

    /**
     * Tear down the testing environment.
     */
    public function tearDown()
    {
        /** @var \Illuminate\View\FileViewFinder $viewFinder */
        $viewFinder = app('view.finder');

        foreach ($viewFinder->getPaths() as $path) {
            $this->clearDirectory($path);
        }

        parent::tearDown();
    }

    /**
     * @param string $path
     */
    protected function clearDirectory($path)
    {
        if (! is_dir($path)) {
            return;
        }

        foreach (scandir($path) as $object) {
            if (in_array($object, ['..', '.', 'welcome.blade.php', 'errors'])) {
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

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return ServiceProvider::class;
    }
}
