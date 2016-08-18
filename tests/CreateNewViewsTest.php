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
}
