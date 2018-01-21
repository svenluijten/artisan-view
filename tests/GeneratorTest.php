<?php

namespace Sven\ArtisanView\Tests;

class GeneratorTest extends TestCase
{
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
    public function it_makes_some_restful_views()
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
