<?php

namespace Sven\ArtisanView\Tests;

class CreateNewViewsTest extends TestCase
{
    /** @test */
    public function it_creates_a_view()
    {
        $this->view->create('index');

        $this->assertTrue(
            $this->filesystem->has('index.blade.php')
        );
    }

    /** @test */
    public function it_dynamically_creates_subfolders()
    {
        $this->view->create('pages.index');
        $this->view->create('pages.about.index');

        $this->assertTrue(
            $this->filesystem->has('pages/index.blade.php')
        );

        $this->assertTrue(
            $this->filesystem->has('pages/about/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_view_to_existing_subfolder()
    {
        $this->view->create('pages.index');
        $this->view->create('pages.about');

        $this->assertTrue(
            $this->filesystem->has('pages/index.blade.php')
        );

        $this->assertTrue(
            $this->filesystem->has('pages/about.blade.php')
        );
    }

    /** @test */
    public function it_creates_a_view_with_a_custom_extension()
    {
        $this->view->create('index', 'html');

        $this->assertTrue(
            $this->filesystem->has('index.html')
        );
    }

    /** @test */
    public function it_can_extend_a_view()
    {
        $this->view->create('index')->extend('layouts.master');

        $this->assertEquals(
            "@extends('layouts.master')".PHP_EOL,
            $this->filesystem->read('index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_section_to_a_view()
    {
        $this->view->create('index')->section('content');

        $this->assertEquals(
            "@section('content')".PHP_EOL.PHP_EOL.
            "@endsection".PHP_EOL,
            $this->filesystem->read('index.blade.php')
        );
    }

    /** @test */
    public function it_adds_multiple_sections_to_one_view()
    {
        $this->view->create('index')->section('content')->section('footer');

        $this->assertEquals(
            "@section('content')".PHP_EOL.PHP_EOL.
            "@endsection".PHP_EOL.
            "@section('footer')".PHP_EOL.PHP_EOL.
            "@endsection".PHP_EOL,
            $this->filesystem->read('index.blade.php')
        );
    }
}
