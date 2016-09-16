<?php

namespace Sven\ArtisanView\Tests\Commands;

use League\Flysystem\Filesystem;
use Orchestra\Testbench\TestCase;
use League\Flysystem\Adapter\Local;
use Sven\ArtisanView\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class MakeViewTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /** @test */
    public function it_creates_a_view()
    {
        // arrange
        $this->app->setBasePath(__DIR__.'/../assets/resources/views');

        // act
        $command = Artisan::call('make:view', [
            'name' => 'test',
        ]);

        // assert
        $this->assertEquals($command, 1);
    }
}
