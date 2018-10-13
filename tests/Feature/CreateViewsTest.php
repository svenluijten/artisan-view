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

    /** @test */
    public function it_creates_a_view_with_a_different_extension()
    {
        $this->artisan(MakeView::class, [
            'name' => 'test',
            '--extension' => 'html.twig',
        ])->assertExitCode(0);

        $this->assertFileExists(__DIR__.'/../resources/views/test.html.twig');
    }

    /** @test */
    public function it_creates_all_four_restful_resource_views()
    {
        $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
        ])->assertExitCode(0);

        $this->assertDirectoryExists(__DIR__.'/../resources/views/posts');
        $this->assertViewExists('posts.index');
        $this->assertViewExists('posts.show');
        $this->assertViewExists('posts.create');
        $this->assertViewExists('posts.edit');
    }

    /** @test */
    public function it_creates_a_resource_in_a_nested_folder()
    {
        $this->artisan(MakeView::class, [
            'name' => 'admin.posts',
            '--resource' => true,
        ])->assertExitCode(0);

        $this->assertViewExists('admin.posts.index');
        $this->assertViewExists('admin.posts.show');
        $this->assertViewExists('admin.posts.create');
        $this->assertViewExists('admin.posts.edit');
    }

    /** @test */
    public function it_creates_part_of_a_restful_resource()
    {
        $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
            '--verb' => ['index', 'show'],
        ])->assertExitCode(0);

        $this->assertViewExists('posts.index');
        $this->assertViewExists('posts.show');
        $this->assertViewNotExists('posts.create');
        $this->assertViewNotExists('posts.edit');
    }

    /** @test */
    public function it_creates_a_resource_with_a_custom_extension()
    {
        $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
            '--extension' => 'html.twig',
        ])->assertExitCode(0);

        $this->assertFileExists(__DIR__.'/../resources/views/posts/index.html.twig');
        $this->assertFileExists(__DIR__.'/../resources/views/posts/show.html.twig');
        $this->assertFileExists(__DIR__.'/../resources/views/posts/create.html.twig');
        $this->assertFileExists(__DIR__.'/../resources/views/posts/edit.html.twig');
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
