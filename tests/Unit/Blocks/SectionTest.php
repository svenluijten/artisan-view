<?php

namespace Sven\ArtisanView\Tests\Unit\Blocks;

use Sven\ArtisanView\Blocks\Section;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Tests\TestCase;

class SectionTest extends TestCase
{
    /** @test */
    public function it_is_applicable_and_renders_a_section(): void
    {
        $config = Config::make()->setSections(['content']);

        $block = new Section($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_renders_multiple_sections_after_each_other(): void
    {
        $config = Config::make()->setSections(['content', 'scripts']);

        $block = new Section($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL.'@section(\'scripts\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_ignores_sections_with_a_colon_in_the_name(): void
    {
        $config = Config::make()->setSections(['content', 'title:My Title']);

        $block = new Section($config);

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_is_not_applicable_when_the_extends_config_option_is_missing(): void
    {
        $config = Config::make();

        $block = new Section($config);

        $this->assertFalse($block->applicable());
    }
}
