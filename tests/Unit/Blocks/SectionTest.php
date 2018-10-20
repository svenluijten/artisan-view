<?php

namespace Sven\ArtisanView\Tests\Unit\Blocks;

use Sven\ArtisanView\Blocks\Section;
use Sven\ArtisanView\Tests\TestCase;

class SectionTest extends TestCase
{
    /** @test */
    public function it_is_applicable_and_renders_a_section(): void
    {
        $block = new Section('content');

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL, $block->render());
    }

    /** @test */
    public function it_ignores_sections_with_a_colon_in_the_name(): void
    {
        $block = new Section('title:My Awesome Title');

        $this->assertFalse($block->applicable());
    }

    /** @test */
    public function an_empty_section_can_be_added(): void
    {
        $block = new Section();

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL, $block->render());
    }
}
