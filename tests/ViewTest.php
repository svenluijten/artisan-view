<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\Exceptions\FileAlreadyExists;
use Sven\ArtisanView\View;

class ViewTest extends ViewTestCase
{
    /** @test */
    public function it_creates_a_view()
    {
        $this->view()->create('index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_a_view_in_a_subfolder()
    {
        $this->view()->create('pages.index');

        $this->assertTrue(
            is_dir(__DIR__.'/assets/pages')
        );

        $this->assertTrue(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_a_view_multiple_levels_deep()
    {
        $this->view()->create('foo.bar.baz');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/foo/bar/baz.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_file_to_existing_subfolder()
    {
        mkdir(__DIR__.'/assets/pages');

        $this->view()->create('pages.index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );
    }

    /** @test */
    public function it_accepts_a_different_extension()
    {
        $this->view()->create('index', 'html');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.html')
        );
    }

    /** @test */
    public function it_extends_a_view()
    {
        $this->view()->create('index')->extend('layout');

        $this->assertEquals(
            '@extends(\'layout\')'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_section()
    {
        $this->view()->create('index')->section('foo');

        $this->assertEquals(
            PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_multiple_sections()
    {
        $this->view()->create('index')->sections('foo,bar');

        $this->assertEquals(
            PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
            PHP_EOL.'@section(\'bar\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_accepts_an_array_of_sections()
    {
        $this->view()->create('about')->sections(['foo', 'bar']);

        $this->assertEquals(
            PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
            PHP_EOL.'@section(\'bar\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/about.blade.php')
        );
    }

    /** @test */
    public function it_adds_sections_with_multiple_method_calls()
    {
        $this->view()->create('foo')->section('title')->section('content');

        $this->assertEquals(
            PHP_EOL.'@section(\'title\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
            PHP_EOL.'@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/foo.blade.php')
        );
    }

    /** @test */
    public function it_adds_inline_sections_with_multiple_method_calls()
    {
        $this->view()->create('foo')->section('title', 'hello world')->section('content');

        $this->assertEquals(
            PHP_EOL.'@section(\'title\', \'hello world\')'.PHP_EOL.
            PHP_EOL.'@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/foo.blade.php')
        );
    }

    /** @test */
    public function it_adds_an_inline_section()
    {
        $this->view()->create('foo')->section('title', 'Hello World');

        $this->assertEquals(
            PHP_EOL.'@section(\'title\', \'Hello World\')'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/foo.blade.php')
        );
    }

    /** @test */
    public function it_adds_multiple_inline_sections()
    {
        $this->view()->create('foo')->sections('title:Hello World,content:nothing');

        $this->assertEquals(
            PHP_EOL.'@section(\'title\', \'Hello World\')'.PHP_EOL.
            PHP_EOL.'@section(\'content\', \'nothing\')'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/foo.blade.php')
        );
    }

    /** @test */
    public function it_adds_one_inline_and_one_full_section()
    {
        $this->view()->create('foo')->sections('title:Hello World,content');

        $this->assertEquals(
            PHP_EOL.'@section(\'title\', \'Hello World\')'.PHP_EOL.
            PHP_EOL.'@section(\'content\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
            file_get_contents(__DIR__.'/assets/foo.blade.php')
        );
    }

    /** @test */
    public function it_extends_a_view_and_adds_sections()
    {
        $this->view()->create('index')->extend('foo')->sections('bar,baz');

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
        $this->view()->create('foo')->extend('');
        $this->view()->create('bar')->sections('');

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

        $this->view()->create('index');
        $this->view()->create('index');
    }

    /** @test */
    public function it_creates_a_resource()
    {
        $this->view()->resource('products');

        foreach (['index', 'show', 'edit', 'create'] as $verb) {
            $this->assertTrue(
                file_exists(__DIR__.'/assets/products/'.$verb.'.blade.php')
            );
        }
    }

    /** @test */
    public function it_only_creates_views_for_the_given_verbs()
    {
        $this->view()->resource('products', 'index,show');
        $this->view()->resource('users', ['index', 'show']);

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
    public function all_views_created_with_resource_extend_a_view()
    {
        $this->view()->resource('products')->extend('layout');

        foreach (['index', 'show', 'edit', 'create'] as $verb) {
            $this->assertEquals(
                '@extends(\'layout\')'.PHP_EOL,
                file_get_contents(__DIR__.'/assets/products/'.$verb.'.blade.php')
            );
        }
    }

    /** @test */
    public function all_views_created_with_resource_have_sections()
    {
        $this->view()->resource('products')->sections('foo,bar');

        foreach (['index', 'show', 'edit', 'create'] as $verb) {
            $this->assertEquals(
                PHP_EOL.'@section(\'foo\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL.
                PHP_EOL.'@section(\'bar\')'.PHP_EOL.PHP_EOL.'@endsection'.PHP_EOL,
                file_get_contents(__DIR__.'/assets/products/'.$verb.'.blade.php')
            );
        }
    }

    /** @test */
    public function it_scraps_a_view()
    {
        $this->view()->create('index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/index.blade.php')
        );

        $this->view()->scrap('index');

        $this->assertFalse(
            file_exists(__DIR__.'/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_scraps_a_view_with_dot_notation()
    {
        $this->view()->create('pages.index');

        $this->assertTrue(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );

        $this->view()->scrap('pages.index');

        $this->assertFalse(
            file_exists(__DIR__.'/assets/pages/index.blade.php')
        );
    }
}
