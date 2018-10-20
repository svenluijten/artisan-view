<?php

namespace Sven\ArtisanView\Tests\Unit\Blocks;

use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Tests\TestCase;

class ExtendTest extends TestCase
{
    /** @test */
    public function it_is_applicable_and_renders_an_extends_tag(): void
    {
        $block = new Extend('app.layout');

        $this->assertTrue($block->applicable());
        $this->assertEquals('@extends(\'app.layout\')'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_is_not_applicable_when_the_extends_config_option_is_missing(): void
    {
        $block = new Extend();

        $this->assertFalse($block->applicable());
    }
}
