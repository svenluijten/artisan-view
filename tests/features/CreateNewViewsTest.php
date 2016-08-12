<?php

namespace Sven\ArtisanView\Tests\Features;

use Sven\ArtisanView\ViewFactory;
use Symfony\Component\Finder\Finder;

class CreateNewViewsTest extends TestCase
{
    /** @test */
    public function it_creates_a_view()
    {
        $view = new ViewFactory(
            new Finder()
        );

        $view->create('index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.blade.php')
        );
    }
}
