<?php

namespace Sven\ArtisanView\Tests\CRUD;

use Sven\ArtisanView\Tests\TestCase;
use Mockery as m;

class CRUDTest extends TestCase
{

    public function test_it_makes_a_new_view()
    {
        $productModel = m::mock('overload:App\Models\Product');
        $productModel->shouldReceive('getFillable')->andReturn([]);
        app()->instance('App\Models\Product', $productModel);

        $this->artisan('make:view', [
            'name' => 'product',
            '--generate' => 'product',
            '--ui' => 'bootstrap',
            '--extends' => 'layouts.app',
            '--resource' => true,
        ]);

        $this->assertViewExists('product/index.blade.php');
        $this->assertViewExists('product/show.blade.php');
        $this->assertViewExists('product/create.blade.php');
        $this->assertViewExists('product/delete.blade.php');
        $this->assertViewExists('product/edit.blade.php');

        $this->artisan('scrap:view', [
            'name' => 'product',
            '--resource' => true,
            '--force' => true,
        ]);

        $this->assertViewNotExists('product/index.blade.php');
        $this->assertViewNotExists('product/show.blade.php');
        $this->assertViewNotExists('product/create.blade.php');
        $this->assertViewNotExists('product/delete.blade.php');
        $this->assertViewNotExists('product/edit.blade.php');
    }

    public function test_it_makes_a_new_view_with_verbs()
    {
        $productModel = m::mock('overload:App\Models\Product');
        $productModel->shouldReceive('getFillable')->andReturn([]);
        app()->instance('App\Models\Product', $productModel);

        $this->artisan('make:view', [
            'name' => 'product',
            '--generate' => 'product',
            '--ui' => 'bootstrap',
            '--extends' => 'layouts.app',
            '--verb' => ['index', 'show', 'create', 'delete', 'edit'],
        ]);

        $this->assertViewExists('product/index.blade.php');
        $this->assertViewExists('product/show.blade.php');
        $this->assertViewExists('product/create.blade.php');
        $this->assertViewExists('product/delete.blade.php');
        $this->assertViewExists('product/edit.blade.php');

        $this->artisan('scrap:view', [
            'name' => 'product',
            '--resource' => true,
            '--force' => true,
        ]);

        $this->assertViewNotExists('product/index.blade.php');
        $this->assertViewNotExists('product/show.blade.php');
        $this->assertViewNotExists('product/create.blade.php');
        $this->assertViewNotExists('product/delete.blade.php');
        $this->assertViewNotExists('product/edit.blade.php');
    }
}
