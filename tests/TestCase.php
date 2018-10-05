<?php

namespace Sven\ArtisanView\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Sven\ArtisanView\ServiceProvider;

abstract class TestCase extends AbstractPackageTestCase
{
    use ViewAssertions;
    use ServiceProviderTrait;
    use InteractsWithConsole;

    /**
     * Tear down the testing environment.
     */
    public function tearDown(): void
    {
        /** @var \Illuminate\View\FileViewFinder $viewFinder */
        $viewFinder = app('view.finder');

        foreach ($viewFinder->getPaths() as $path) {
            $this->clearDirectory($path);
        }

        parent::tearDown();
    }

    /**
     * @param  string  $path
     */
    protected function clearDirectory($path)
    {
        if (!is_dir($path)) {
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
     * @param  string  $name
     * @return string
     */
    protected function view($name)
    {
        return file_get_contents($this->pathToView($name));
    }

    /**
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return string
     */
    protected function getServiceProviderClass()
    {
        return ServiceProvider::class;
    }

    /**
     * @param  string  $name
     * @param  string  $contents
     */
    protected function makeView($name, $contents = '')
    {
        $this->artisan('make:view', ['name' => $name]);

        file_put_contents($this->pathToView($name), $contents);
    }

    /**
     * @param  string  $name
     * @return string
     */
    protected function pathToView($name)
    {
        /** @var Factory $view */
        $view = $this->app->make(Factory::class);

        return $view->getFinder()->find($name);
    }
}
