<?php

namespace Sven\ArtisanView\Tests\Unit\Blocks;

use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Tests\TestCase;

class InlineSectionTest extends TestCase
{
    /** @test */
    public function it_is_applicable_and_renders_an_inline_section(): void
    {
        $block = new InlineSection('title:My Title');

        $this->assertTrue($block->applicable());
        $this->assertEquals('@section(\'title\', \'My Title\')', $block->render());
    }

    /** @test */
    public function it_ignores_sections_without_a_colon_in_the_name(): void
    {
        $block = new InlineSection('content');

        $this->assertFalse($block->applicable());
    }
}
