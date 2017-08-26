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

        $this->assertTrue($this->view->exists('index'));
    }

    /** @test */
    public function it_makes_a_view_with_a_different_extension()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--extension' => 'html.twig',
        ]);

        $this->assertFileExists(base_path('resources/views/index.html.twig'));
    }

    /** @test */
    public function it_makes_a_view_in_a_subfolder()
    {
        $this->artisan('make:view', [
            'name' => 'pages.about',
        ]);

        $this->assertTrue($this->view->exists('pages.about'));
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
}
