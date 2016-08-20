<?php

namespace Sven\ArtisanView\Tests;

class ViewFormatTest extends TestCase
{
    /** @test */
    public function it_can_extend_a_view()
    {
        $this->view->create('index')->extend('layouts.master');

        $this->assertEquals(
            '@extends(\'layouts.master\')'.PHP_EOL,
            $this->filesystem->read('index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_section_to_a_view()
    {
        $this->view->create('index')->section('content');

        $this->assertEquals(
            PHP_EOL.
            '@section(\'content\')'.PHP_EOL.
            PHP_EOL.
            '@endsection'.PHP_EOL,
            $this->filesystem->read('index.blade.php')
        );
    }

    /** @test */
    public function it_adds_multiple_sections_to_a_view()
    {
        $this->view->create('index')->section('content')->section('footer');

        $this->assertEquals(
            PHP_EOL.
            '@section(\'content\')'.PHP_EOL.
            PHP_EOL.
            '@endsection'.PHP_EOL.
            PHP_EOL.
            '@section(\'footer\')'.PHP_EOL.
            PHP_EOL.
            '@endsection'.
            PHP_EOL,
            $this->filesystem->read('index.blade.php')
        );
    }

    /** @test */
    public function it_adds_sections_inline()
    {
        $this->view->create('index')->section('title', 'My awesome page');

        $this->assertEquals(
            PHP_EOL.
            '@section(\'title\', \'My awesome page\')'.PHP_EOL,
            $this->filesystem->read('index.blade.php')
       );
    }
}
