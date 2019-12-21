<?php

namespace Sven\ArtisanView\Tests\Feature;

use InvalidArgumentException;
use Sven\ArtisanView\Commands\MakeView;
use Sven\ArtisanView\Commands\ScrapView;
use Sven\ArtisanView\Tests\TestCase;
use Sven\LaravelTestingUtils\InteractsWithViews;

class ScrapViewsTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    public function it_asks_the_user_if_they_are_sure_they_want_to_remove_the_view(): void
    {
        /** @var \Illuminate\Foundation\Testing\PendingCommand $command */
        $command = $this->artisan(ScrapView::class, ['name' => 'index']);

        $command->assertExitCode(0)
            ->expectsQuestion('Are you sure you want to remove that view / those views?', 'y');
    }

    /** @test */
    public function it_does_not_remove_the_view_if_no_is_answered(): void
    {
        $this->artisan(MakeView::class, ['name' => 'index']);

        $this->assertViewExists('index');

        /** @var \Illuminate\Foundation\Testing\PendingCommand $command */
        $command = $this->artisan(ScrapView::class, ['name' => 'index']);

        $command->assertExitCode(0)
            ->expectsQuestion('Are you sure you want to remove that view / those views?', false)
            ->expectsOutput('Okay, nothing was changed.');

        $this->assertViewExists('index');
    }

    /** @test */
    public function it_does_not_ask_for_confirmation_if_the_force_flag_is_passed(): void
    {
        /** @var \Illuminate\Foundation\Testing\PendingCommand $command */
        $command = $this->artisan(ScrapView::class, ['name' => 'index', '--force' => true]);

        $command->assertExitCode(0);
    }

    /** @test */
    public function it_asks_where_the_view_should_be_scrapped_from_if_more_than_one_view_path_is_configured(): void
    {
        $this->app->make('config')->set('view.paths', [
            __DIR__.'/../resources/views',
            __DIR__.'/../resources/does-not-exist',
        ]);

        /** @var \Illuminate\Foundation\Testing\PendingCommand $command */
        $command = $this->artisan(ScrapView::class, [
            'name' => 'index',
            '--force' => true,
        ]);

        $command->assertExitCode(0)
            ->expectsQuestion('Where should the view be scrapped from?', __DIR__.'/../resources/views');
    }

    /** @test */
    public function it_throws_an_exception_if_no_view_paths_are_configured_when_scrapping_a_view(): void
    {
        $this->app->make('config')->set('view.paths', []);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There are no paths configured to remove the view(s) from.');

        $this->artisan(ScrapView::class, [
            'name' => 'index',
            '--force' => true,
        ]);
    }

    /** @test */
    public function it_can_scrap_a_single_view(): void
    {
        $this->assertViewNotExists('testing');

        $this->artisan(MakeView::class, ['name' => 'testing']);

        $this->assertViewExists('testing');

        $this->artisan(ScrapView::class, ['name' => 'testing', '--force' => true]);

        $this->assertViewNotExists('testing', 'A view with that name does not exist');
    }

    /** @test */
    public function it_removes_empty_directories_it_leaves_behind(): void
    {
        $this->artisan(MakeView::class, ['name' => 'posts.index']);

        $this->assertViewExists('posts.index');
        $this->assertDirectoryExists(__DIR__.'/../resources/views/posts');

        $this->artisan(ScrapView::class, ['name' => 'posts.index', '--force' => true]);

        $this->assertViewNotExists('posts.index');
        $this->assertDirectoryNotExists(__DIR__.'/../resources/views/posts');
    }

    /** @test */
    public function it_can_scrap_a_restful_resource(): void
    {
        $this->assertViewNotExists('posts.index');
        $this->assertViewNotExists('posts.show');
        $this->assertViewNotExists('posts.edit');
        $this->assertViewNotExists('posts.create');

        $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
        ]);

        $this->assertViewExists('posts.index');
        $this->assertViewExists('posts.show');
        $this->assertViewExists('posts.edit');
        $this->assertViewExists('posts.create');

        $this->artisan(ScrapView::class, [
            'name' => 'posts',
            '--force' => true,
            '--resource' => true,
        ]);

        $this->assertViewNotExists('posts.index');
        $this->assertViewNotExists('posts.show');
        $this->assertViewNotExists('posts.edit');
        $this->assertViewNotExists('posts.create');
    }

    /** @test */
    public function it_can_scrap_only_part_of_a_restful_resource(): void
    {
        $this->assertViewNotExists('posts.index');
        $this->assertViewNotExists('posts.show');
        $this->assertViewNotExists('posts.edit');
        $this->assertViewNotExists('posts.create');

        $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
        ]);

        $this->assertViewExists('posts.index');
        $this->assertViewExists('posts.show');
        $this->assertViewExists('posts.edit');
        $this->assertViewExists('posts.create');

        $this->artisan(ScrapView::class, [
            'name' => 'posts',
            '--force' => true,
            '--resource' => true,
            '--verb' => ['index', 'show'],
        ]);

        $this->assertViewNotExists('posts.index');
        $this->assertViewNotExists('posts.show');
        $this->assertViewExists('posts.edit');
        $this->assertViewExists('posts.create');
    }
}
