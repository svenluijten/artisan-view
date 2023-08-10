<?php

namespace Sven\ArtisanView\Tests\Feature;

use InvalidArgumentException;
use Sven\ArtisanView\Commands\MakeView;
use Sven\ArtisanView\Exceptions\ViewAlreadyExists;
use Sven\ArtisanView\Tests\TestCase;
use Sven\LaravelViewAssertions\InteractsWithViews;

class CreateViewsTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    public function it_creates_a_view(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, ['name' => 'testing']);

        $command->assertExitCode(0);
        unset($command);

        $this->assertViewExists('testing');
    }

    /** @test */
    public function it_does_not_overwrite_an_existing_view_if_it_exists(): void
    {
        $this->artisan(MakeView::class, [
            'name' => 'testing',
            '--extends' => 'layouts.app',
        ]);

        $this->assertViewExists('testing');
        $this->assertViewEquals("@extends('layouts.app')".PHP_EOL.PHP_EOL, 'testing');

        $expectedPath = realpath(__DIR__.'/../resources/views/testing.blade.php');

        $this->expectException(ViewAlreadyExists::class);
        $this->expectExceptionMessage('A view already exists at "'.$expectedPath.'".');

        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, ['name' => 'testing']);

        $command->assertExitCode(1);
        unset($command);

        $this->assertViewExists('testing');
        $this->assertViewEquals("@extends('layouts.app')".PHP_EOL.PHP_EOL, 'testing');
    }

    /** @test */
    public function it_makes_a_view_in_a_nested_folder(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, ['name' => 'foo.bar']);

        $command->assertExitCode(0);
        unset($command);

        $this->assertViewExists('foo.bar');
        $this->assertDirectoryExists(__DIR__.'/../resources/views/foo');
    }

    /** @test */
    public function it_creates_a_view_with_a_different_extension(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, [
            'name' => 'test',
            '--extension' => 'html.twig',
        ]);

        $command->assertExitCode(0);
        unset($command);

        $this->assertFileExists(__DIR__.'/../resources/views/test.html.twig');
    }

    /** @test */
    public function it_creates_all_four_restful_resource_views(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
        ]);

        $command->assertExitCode(0);
        unset($command);

        $this->assertDirectoryExists(__DIR__.'/../resources/views/posts');
        $this->assertViewExists('posts.index');
        $this->assertViewExists('posts.show');
        $this->assertViewExists('posts.create');
        $this->assertViewExists('posts.edit');
    }

    /** @test */
    public function it_creates_a_resource_in_a_nested_folder(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, [
            'name' => 'admin.posts',
            '--resource' => true,
        ]);

        $command->assertExitCode(0);
        unset($command);

        $this->assertViewExists('admin.posts.index');
        $this->assertViewExists('admin.posts.show');
        $this->assertViewExists('admin.posts.create');
        $this->assertViewExists('admin.posts.edit');
    }

    /** @test */
    public function it_creates_part_of_a_restful_resource(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
            '--verb' => ['index', 'show'],
        ]);

        $command->assertExitCode(0);
        unset($command);

        $this->assertViewExists('posts.index');
        $this->assertViewExists('posts.show');
        $this->assertViewNotExists('posts.create');
        $this->assertViewNotExists('posts.edit');
    }

    /** @test */
    public function it_creates_a_resource_with_a_custom_extension(): void
    {
        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, [
            'name' => 'posts',
            '--resource' => true,
            '--extension' => 'html.twig',
        ]);

        $command->assertExitCode(0);
        unset($command);

        $this->assertFileExists(__DIR__.'/../resources/views/posts/index.html.twig');
        $this->assertFileExists(__DIR__.'/../resources/views/posts/show.html.twig');
        $this->assertFileExists(__DIR__.'/../resources/views/posts/create.html.twig');
        $this->assertFileExists(__DIR__.'/../resources/views/posts/edit.html.twig');
    }

    /** @test */
    public function it_asks_where_the_view_should_be_created_if_more_than_one_view_path_is_configured(): void
    {
        $this->app->make('config')->set('view.paths', [
            __DIR__.'/../resources/views',
            __DIR__.'/../resources/does-not-exist',
        ]);

        /** @var \Illuminate\Testing\PendingCommand $command */
        $command = $this->artisan(MakeView::class, [
            'name' => 'index',
        ]);

        $command->assertExitCode(0)
            ->expectsQuestion('Where should the view be created?', __DIR__.'/../resources/views');

        unset($command);

        $this->assertViewExists('index');
    }

    /** @test */
    public function it_throws_an_exception_if_no_view_paths_are_configured_when_creating_a_view(): void
    {
        $this->app->make('config')->set('view.paths', []);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There are no paths configured to store the view(s) in.');

        $this->artisan(MakeView::class, [
            'name' => 'index',
        ]);
    }
}
