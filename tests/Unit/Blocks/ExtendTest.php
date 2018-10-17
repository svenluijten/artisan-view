<?php

namespace Sven\ArtisanView\Tests\Unit\Blocks;

use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Tests\TestCase;

class ExtendTest extends TestCase
{
    /** @test */
    public function it_is_applicable_and_renders_an_extends_tag(): void
    {
        $config = Config::make()->setExtends('app.layout');

        $block = new Extend($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@extends(\'app.layout\')'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_is_not_applicable_when_the_extends_config_option_is_missing(): void
    {
        $config = Config::make();

        $block = new Extend($config);

        $this->assertFalse($block->applicable());
    }
}
