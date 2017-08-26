<?php

namespace Sven\ArtisanView\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;

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
}
