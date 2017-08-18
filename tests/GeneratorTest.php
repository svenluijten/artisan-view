<?php

namespace Sven\ArtisanView\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\View\Factory;

class GeneratorTest extends TestCase
{
    use InteractsWithConsole;

    /** @test */
    public function it_makes_a_new_view()
    {
        $this->artisan('make:view', [
            'name' => 'index',
        ]);

        $this->assertTrue($this->app->make(Factory::class)->exists('index'));
    }
}
