<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\Blocks;

class FillViewsTest extends TestCase
{
    /** @test */
    public function it_extends_another_view()
    {
        $this->artisan('make:view', [
            'name' => 'pages.contact',
            '--extends' => 'layouts.app',
        ]);

        $block = new Blocks\Extend('layouts.app');

        $this->assertContains($block->render(), $this->view('pages.contact'));
    }

    /** @test */
    public function it_includes_a_section()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--section' => 'content',
        ]);

        $block = new Blocks\Section('content');

        $this->assertContains($block->render(), $this->view('index'));
    }

    /** @test */
    public function it_includes_multiple_sections()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--section' => ['content', 'footer'],
        ]);

        $firstBlock = new Blocks\Section('content');
        $secondBlock = new Blocks\Section('footer');

        $this->assertContains($firstBlock->render().$secondBlock->render(), $this->view('index'));
    }

    /** @test */
    public function it_includes_an_inline_section()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--section' => 'title:Hello world',
        ]);

        $block = new Blocks\InlineSection('title', 'Hello world');

        $this->assertContains($block->render(), $this->view('index'));
    }

    /** @test */
    public function it_reads_yield_directives_from_the_parent_view()
    {
        $this->makeView('layout', '@yield(\'content\')'.PHP_EOL.PHP_EOL.'@yield(\'something\')');

        $this->artisan('make:view', [
            'name' => 'index',
            '--extends' => 'layout',
            '--with-yields' => true,
        ]);

        $blockOne = new Blocks\Section('content');
        $blockTwo = new Blocks\Section('something');

        $this->assertContains($blockOne->render().$blockTwo->render(), $this->view('index'));
    }

    /** @test */
    public function it_reads_stack_directives_from_the_parent_view()
    {
        $this->makeView('layout', '@stack(\'javascripts\')');

        $this->artisan('make:view', [
            'name' => 'index',
            '--extends' => 'layout',
            '--with-stacks' => true,
        ]);

        $pushBlock = new Blocks\Push('javascripts');

        $this->assertContains($pushBlock->render(), $this->view('index'));
    }

    /** @test */
    public function it_ignores_yield_and_stack_options_if_the_parent_view_does_not_exist_yet()
    {
        $this->artisan('make:view', [
            'name' => 'index',
            '--extends' => 'doesnotexist',
            '--with-yields' => true,
            '--with-stacks' => true,
        ]);

        $this->assertNotContains('@section', $this->view('index'));
        $this->assertNotContains('@push', $this->view('index'));
    }
}
