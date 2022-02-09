<?php

namespace Sven\ArtisanView\Tests\Unit;

use Sven\ArtisanView\BlockBuilder;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Tests\TestCase;

class BlockBuilderTest extends TestCase
{
    /**
     * @param  \Sven\ArtisanView\Config  $config
     * @param  string  $contents
     *
     * @test
     * @dataProvider orderProvider
     */
    public function it_renders_out_the_blocks_in_the_right_order(Config $config, string $contents): void
    {
        $builder = BlockBuilder::make();

        $this->assertEquals($contents, $builder->build($config));
    }

    public function orderProvider(): array
    {
        return [
            'extend, section, and inline section' => [
                Config::make()->setExtends('layouts.master')->setSections(['content', 'title:My Title']),
                "@extends('layouts.master')".PHP_EOL.PHP_EOL."@section('content')".PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL."@section('title', 'My Title')".PHP_EOL.PHP_EOL,
            ],
            'empty config' => [
                Config::make(),
                '',
            ],
            'inline section without content' => [
                Config::make()->setSections(['title:']),
                "@section('title', '')".PHP_EOL.PHP_EOL,
            ],
            'empty section' => [
                Config::make()->setSections(['']),
                "@section('')".PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.PHP_EOL,
            ],
        ];
    }
}
