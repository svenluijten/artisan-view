![artisan-view](https://cloud.githubusercontent.com/assets/11269635/14457826/a3bde82a-00ad-11e6-8161-0c218937156a.jpg)

# Artisan View
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-circleci]][link-circleci]
[![StyleCI][ico-styleci]][link-styleci]

This package adds a handful of view-related commands to Artisan in your Laravel
project. Generate blade files that extend other views, scaffold out sections
to add to those templates, and more. All from the command line we know and love!

## Index
- [Installation](#installation)
  - [Downloading](#downloading)
  - [Registering the service provider](#registering-the-service-provider)
- [Usage](#usage)
  - [Creating views](#creating-views)
  - [Extending and sections](#extending-and-sections)
  - [REST resources](#rest-resources)
  - [Scrapping views](#scrapping-views)
  - [Mix and match](#mix-and-match)
- [Contributing](#contributing)
- [License](#license)

## Installation
You'll have to follow a couple of simple steps to install this package.

### Downloading
Via [composer](http://getcomposer.org):

```bash
$ composer require sven/artisan-view:^2.0 --dev
```

Or add the package to your development dependencies in `composer.json` and run
`composer update sven/artisan-view` to download the package:

```json
{
    "require-dev": {
        "sven/artisan-view": "^2.0"
    }
}
```

### Registering the service provider
If you're using Laravel 5.5, you can skip this step. The service provider will have already been registered
thanks to auto-discovery. 

Otherwise, register `Sven\ArtisanView\ServiceProvider::class` manually in your `AppServiceProvider`'s
`register` method:

```php
public function register()
{
    if ($this->app->environment() !== 'production') {
        $this->app->register(\Sven\ArtisanView\ServiceProvider::class);
    }    
}
```

## Usage
If you now run `php artisan` you will see two new commands in the list:
- `make:view`
- `scrap:view`

### Creating views
```bash
# Create a view 'index.blade.php' in the default directory
$ php artisan make:view index

# Create a view 'index.blade.php' in a subdirectory ('pages')
$ php artisan make:view pages.index

# Create a view with a different file extension ('index.html')
$ php artisan make:view index --extension=html
```

### Extending and sections
```bash
# Extend an existing view
$ php artisan make:view index --extends=app

# Add a section to the view
$ php artisan make:view index --section=content

# Add multiple sections to the view
$ php artisan make:view index --section=title --section=content

# Add an inline section to the view
# Remember to add quotes around the section if you want to use spaces
$ php artisan make:view index --section="title:Hello world"

# Automatically create sections for each @yield statement in the extended view
$ php artisan make:view index --extends=app --with-yields

# Automatically create push sections for each @stack statement in the extended view
$ php artisan make:view index --extends=app --with-stacks
```

### REST resources
```bash
# Create a resource called 'products'
$ php artisan make:view products --resource

# Create a resource with only specific verbs
$ php artisan make:view products --resource --verb=index --verb=create --verb=edit
```

### Scrapping views
```bash
# Remove the view 'index.blade.php'
$ php artisan scrap:view index

# Remove the view by dot notation
$ php artisan scrap:view pages.index
```

This will ask you if you're sure. To skip this question, pass the `--force` flag:

```bash
# Don't ask for confirmation
$ php artisan scrap:view index --force
```

### Scrapping a REST resource
```bash
# Remove the resource called 'products'
$ php artisan scrap:view products --resource
```

This will remove the views `products.index`, `products.show`, `products.create`, and `products.edit`. If the directory
`products/` is empty after doing that, it will also be deleted.

You can scrap part of a resource by adding `--verb` flags:

```bash
# Remove the 'products.create' and 'products.edit' views.
$ php artisan scrap:view products --resource --verb=create --verb=edit
```

### Mix and match
Of course, all the options work well together like you'd expect. So the following command...

```bash
$ php artisan make:view products --resource --extends=app --section="title:This is my title" --section=content
```

... will put the following contents in `products/index.blade.php`, `products/edit.blade.php`, `products/create.blade.php`,
and `products/show.blade.php`:

```blade
@extends('app')

@section('title', 'This is my title')

@section('content')

@endsection
```

## Contributing
All contributions (in the form on pull requests, issues and feature-requests) are
welcome. See the [contributors page](../../graphs/contributors) for all contributors.

## License
`sven/artisan-view` is licenced under the MIT License (MIT). Please see the
[license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sven/artisan-view.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-green.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sven/artisan-view.svg?style=flat-square
[ico-circleci]: https://img.shields.io/circleci/project/github/svenluijten/artisan-view.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/56054783/shield

[link-packagist]: https://packagist.org/packages/sven/artisan-view
[link-downloads]: https://packagist.org/packages/sven/artisan-view
[link-circleci]: https://circleci.com/gh/svenluijten/artisan-view
[link-styleci]: https://styleci.io/repos/56054783
