<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\Exceptions\FileDoesNotExist;
use Sven\ArtisanView\Exceptions\FileAlreadyExists;

class ViewTest extends ViewTestCase
{
    /** @test */
    public function it_creates_a_view()
    {
        $this->view->create('index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_a_view_in_a_subfolder()
    {
        $this->view->create('pages.index');

        $this->assertTrue(
            is_dir(__DIR__.'/assets/pages')
        );

        $this->assertTrue(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_file_to_existing_subfolder()
    {
        mkdir(__DIR__.'/assets/pages');

        $this->view->create('pages.index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );
    }

    /** @test */
    public function it_accepts_a_different_extension()
    {
        $this->view->create('index', 'html');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.html')
        );
    }

    /** @test */
    public function it_extends_a_view()
    {
        $this->view->create('index')->extend('layout');

        $this->assertEquals(
            '@extends(\'layout\')'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_section()
    {
        $this->view->create('index')->sections('foo');

        $this->assertEquals(
            PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_multiple_sections()
    {
        $this->view->create('index')->sections('foo,bar');

        $this->assertEquals(
            PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
            PHP_EOL.'@section(\'bar\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_accepts_an_array_of_sections()
    {
        $this->view->create('about')->sections(['foo', 'bar']);

        $this->assertEquals(
            PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
            PHP_EOL.'@section(\'bar\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/about.blade.php')
        );
    }

    /** @test */
    public function it_extends_a_view_and_adds_sections()
    {
        $this->view->create('index')->extend('foo')->sections('bar,baz');

        $this->assertEquals(
            '@extends(\'foo\')'.PHP_EOL.
            PHP_EOL.'@section(\'bar\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
            PHP_EOL.'@section(\'baz\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_empty_view_if_extend_or_sections_are_empty()
    {
        $this->view->create('foo')->extend('');
        $this->view->create('bar')->sections('');

        $this->assertEquals(
            '',
            file_get_contents(__DIR__.'/assets/foo.blade.php')
        );

        $this->assertEquals(
            '',
            file_get_contents(__DIR__.'/assets/bar.blade.php')
        );
    }

    /** @test */
    public function it_throws_an_exception_if_file_already_exists()
    {
        $this->setExpectedException(FileAlreadyExists::class);

        $this->view->create('index');
        $this->view->create('index');
    }

    /** @test */
    public function it_creates_a_resource()
    {
        $this->view->resource('products');

        foreach (['index', 'show', 'edit', 'create'] as $verb) {
            $this->assertTrue(
                file_exists(__DIR__.'/assets/products/'.$verb.'.blade.php')
            );
        }
    }

    /** @test */
    public function it_only_creates_views_for_the_given_verbs()
    {
        $this->view->resource('products', 'index,show');
        $this->view->resource('users', ['index', 'show']);

        foreach (['index', 'show'] as $verb) {
            $this->assertTrue(
                file_exists(__DIR__.'/assets/products/'.$verb.'.blade.php')
            );

            $this->assertTrue(
                file_exists(__DIR__.'/assets/users/'.$verb.'.blade.php')
            );
        }

        $this->assertFalse(
            file_exists(__DIR__.'/assets/products/edit.blade.php')
        );

        $this->assertFalse(
            file_exists(__DIR__.'/assets/users/edit.blade.php')
        );
    }

    /** @test */
    public function it_scraps_a_view()
    {
        $this->view->create('index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.blade.php')
        );

        $this->view->scrap('index');

        $this->assertFalse(
            file_exists(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_scraps_a_view_with_dot_notation()
    {
        $this->view->create('pages.index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );

        $this->view->scrap('pages.index');

        $this->assertFalse(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );
    }
}
