<?php

namespace Sven\ArtisanView\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Blocks\Section;

class GeneratorTest extends TestCase
{
    use InteractsWithConsole;

    /** @test */
    public function it_makes_a_new_view()
    {
        $this->artisan('make:view', [
            'name' => 'index',
        ]);

        $this->assertViewExists('index.blade.php');
    }

    /** @test */
    public function it_makes_a_view_with_a_different_extension()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--extension' => 'html.twig',
        ]);

        $this->assertViewExists('index.html.twig');
    }

    /** @test */
    public function it_makes_a_view_in_a_subfolder()
    {
        $this->artisan('make:view', [
            'name' => 'pages.about',
        ]);

        $this->assertViewExists('pages/about.blade.php');
    }

    /** @test */
    public function the_view_it_generates_is_empty()
    {
        $this->artisan('make:view', [
            'name' => 'index',
        ]);

        $this->assertEmpty($this->view('index'));
    }

    /** @test */
    public function it_extends_another_view()
    {
        $this->artisan('make:view', [
            'name' => 'pages.contact',
            '--extends' => 'layouts.app',
        ]);

        $block = new Extend('layouts.app');

        $this->assertContains($block->render(), $this->view('pages.contact'));
    }

    /** @test */
    public function it_includes_a_section()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--section' => 'content',
        ]);

        $block = new Section('content');

        $this->assertContains($block->render(), $this->view('index'));
    }

    /** @test */
    public function it_includes_multiple_sections()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--section' => ['content', 'footer'],
        ]);

        $firstBlock = new Section('content');
        $secondBlock = new Section('footer');

        $this->assertContains($firstBlock->render().$secondBlock->render(), $this->view('index'));
    }

    /** @test */
    public function it_includes_an_inline_section()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--section' => 'title:Hello world',
        ]);

        $block = new InlineSection('title', 'Hello world');

        $this->assertContains($block->render(), $this->view('index'));
    }

    /** @test */
    public function it_makes_all_restful_views()
    {
        $this->artisan('make:view', [
            'name' => 'products',
            '--resource' => true,
        ]);

        $this->assertViewExists('products/index.blade.php');
        $this->assertViewExists('products/show.blade.php');
        $this->assertViewExists('products/create.blade.php');
        $this->assertViewExists('products/edit.blade.php');
    }

    /** @test */
    public function it_makes_some_restful_routes()
    {
        $this->artisan('make:view', [
            'name' => 'products',
            '--resource' => true,
            '--verb' => ['show', 'create'],
        ]);

        $this->assertViewExists('products/show.blade.php');
        $this->assertViewExists('products/create.blade.php');
        $this->assertViewNotExists('products/edit.blade.php');
        $this->assertViewNotExists('products/index.blade.php');
    }
}
