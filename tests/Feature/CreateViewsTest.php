<?php

namespace Sven\ArtisanView\Tests\Feature;

use Sven\ArtisanView\Commands\MakeView;
use Sven\ArtisanView\Tests\TestCase;
use Sven\LaravelTestingUtils\InteractsWithViews;

class CreateViewsTest extends TestCase
{
    use InteractsWithViews;

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('view.paths', [
            __DIR__.'/../resources/views',
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

    /** @test */
    public function it_creates_a_view(): void
    {
        $this->artisan(MakeView::class, ['name' => 'testing'])
            ->assertExitCode(0);

        $this->assertViewExists('testing');
    }

    /** @test */
    public function it_makes_a_view_in_a_nested_folder(): void
    {
        $this->artisan(MakeView::class, ['name' => 'foo.bar'])
            ->assertExitCode(0);

        $this->assertViewExists('foo.bar');
        $this->assertDirectoryExists(__DIR__.'/../resources/views/foo');
    }

    private function clearDirectory(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }

        foreach (scandir($path, SCANDIR_SORT_NONE) as $object) {
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
