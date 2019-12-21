<?php

namespace Sven\ArtisanView\Tests\Feature;

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
        $this->artisan(ScrapView::class, ['name' => 'index'])
            ->expectsQuestion('Are you sure you want to remove that view / those views?', true)
            ->assertExitCode(0);
    }

    /** @test */
    public function it_does_not_remove_the_view_if_no_is_answered(): void
    {
        $this->artisan(ScrapView::class, ['name' => 'index'])
            ->expectsQuestion('Are you sure you want to remove that view / those views?', false)
            ->expectsOutput('Okay, nothing was changed.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_does_not_ask_for_confirmation_if_the_force_flag_is_passed(): void
    {
        $this->artisan(ScrapView::class, ['name' => 'index', '--force' => true])
            ->assertExitCode(0);
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
}
