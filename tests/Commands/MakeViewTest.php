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
        $adapter = new Local(__DIR__.'/../assets/');
        $filesystem = new Filesystem($adapter);

        $this->app->setBasePath(__DIR__.'/../assets/');

        $filesystem->write('resources/views/foo.txt', '');
        $filesystem->delete('resources/views/foo.txt');

        $command = Artisan::call('make:view', [
            'name' => 'test'
        ]);

        // assert
        $this->assertEquals(
            $command,
            1
        );
    }
}