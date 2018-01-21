<?php

namespace Sven\ArtisanView\Tests;

use Illuminate\Contracts\Console\Kernel;
use Mockery as m;

class ScrapTest extends TestCase
{
    /** @test */
    public function it_scraps_an_existing_view()
    {
        $this->makeView('index');

        $this->assertViewExists('index.blade.php');

        $command = m::mock('\Sven\ArtisanView\Commands\ScrapView[confirm]');
        $command->shouldReceive('confirm')->once()->andReturn(true);

        $this->app->make(Kernel::class)->registerCommand($command);

        $this->artisan('scrap:view', [
            'name' => 'index',
        ]);

        $this->assertViewNotExists('index.blade.php');
    }

    /** @test */
    public function it_forcefully_scraps_a_view()
    {
        $this->makeView('index');

        $this->assertViewExists('index.blade.php');

        $this->artisan('scrap:view', [
            'name' => 'index',
            '--force' => true,
        ]);

        $this->assertViewNotExists('index.blade.php');
    }

    /** @test */
    public function it_scraps_an_entire_resource()
    {
        $this->artisan('make:view', [
            'name' => 'products',
            '--resource' => true,
        ]);

        $this->assertViewExists('products/index.blade.php');
        $this->assertViewExists('products/show.blade.php');
        $this->assertViewExists('products/edit.blade.php');
        $this->assertViewExists('products/create.blade.php');

        $this->artisan('scrap:view', [
            'name' => 'products',
            '--force' => true,
            '--resource' => true,
        ]);

        $this->assertViewNotExists('products/index.blade.php');
        $this->assertViewNotExists('products/show.blade.php');
        $this->assertViewNotExists('products/edit.blade.php');
        $this->assertViewNotExists('products/create.blade.php');
    }

    /** @test */
    public function it_scraps_the_parent_folder_when_a_resource_is_scrapped()
    {
        $this->artisan('make:view', [
            'name' => 'products',
            '--resource' => true,
        ]);

        $this->assertViewExists('products');

        $this->artisan('scrap:view', [
            'name' => 'products',
            '--resource' => true,
            '--force' => true,
        ]);

        $this->assertViewNotExists('products');
    }

    /** @test */
    public function it_scraps_only_part_of_a_resource()
    {
        $this->artisan('make:view', [
            'name' => 'products',
            '--resource' => true,
        ]);

        $this->assertViewExists('products');

        $this->artisan('scrap:view', [
            'name' => 'products',
            '--resource' => true,
            '--verb' => ['create', 'edit'],
            '--force' => true,
        ]);

        $this->assertViewNotExists('products/create.blade.php');
        $this->assertViewNotExists('products/edit.blade.php');
        $this->assertViewExists('products/show.blade.php');
        $this->assertViewExists('products/index.blade.php');
    }
}
