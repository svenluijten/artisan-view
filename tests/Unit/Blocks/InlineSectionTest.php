<?php

namespace Sven\ArtisanView\Tests\Unit\Blocks;

use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Tests\TestCase;

class InlineSectionTest extends TestCase
{
    /** @test */
    public function it_is_applicable_and_renders_an_inline_section(): void
    {
        $config = Config::make()->setSections(['title:My Title']);

        $block = new InlineSection($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'title\', \'My Title\')'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_renders_multiple_inline_sections_after_each_other(): void
    {
        $config = Config::make()->setSections(['title:My Title', 'description:This Is An Amazing Website!']);

        $block = new InlineSection($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'title\', \'My Title\')'.PHP_EOL.PHP_EOL.'@section(\'description\', \'This Is An Amazing Website!\')'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_ignores_sections_without_a_colon_in_the_name(): void
    {
        $config = Config::make()->setSections(['content', 'title:My Title']);

        $block = new InlineSection($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'title\', \'My Title\')'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_is_not_applicable_when_the_extends_config_option_is_missing(): void
    {
        $config = Config::make();

        $block = new InlineSection($config);

        $this->assertFalse($block->applicable());
    }
}
