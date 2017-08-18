<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Blocks\Section;

class BlocksTest extends TestCase
{
    /** @test */
    public function it_renders_an_extends_block()
    {
        $block = new Extend('layouts.app');

        $this->assertEquals(
            "@extends('layouts.app')" . PHP_EOL . PHP_EOL,
            $block->render()
        );
    }

    /** @test */
    public function it_renders_a_section_block()
    {
        $block = new Section('content');

        $this->assertEquals(
            "@section('content')" . PHP_EOL . PHP_EOL . "@endsection" . PHP_EOL . PHP_EOL,
            $block->render()
        );
    }

    /** @test */
    public function it_renders_an_inline_section()
    {
        $block = new InlineSection('title', 'Some title');

        $this->assertEquals(
            "@section('title', 'Some title')" . PHP_EOL . PHP_EOL,
            $block->render()
        );
    }
}
