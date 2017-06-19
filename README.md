![artisan-view](https://cloud.githubusercontent.com/assets/11269635/14457826/a3bde82a-00ad-11e6-8161-0c218937156a.jpg)

# Artisan View

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Code Climate][ico-codeclimate]][link-codeclimate]
[![Code Quality][ico-quality]][link-quality]
[![StyleCI][ico-styleci]][link-styleci]

This package adds a couple of view-related commands to Artisan in your Laravel
projects. It is super simple to use and easy to understand for everyone.

## Installation
Via [composer](http://getcomposer.org):

```bash
$ composer require sven/artisan-view
```

Or add the package to your dependencies in `composer.json` and run
`composer update` to download the package:

```json
{
    "require": {
        "sven/artisan-view": "^1.0"
    }
}
```

**Note:** If you're using Laravel 5.5, you're done! The service provider is automatically registered in the container,
thanks to [auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518).

Next, add the `ArtisanViewServiceProvider` to your `providers` array in `config/app.php`:

```php
// config/app.php
'providers' => [
    ...
    Sven\ArtisanView\ArtisanViewServiceProvider::class,
];
```

If you want to only load this service provider in a specific environment (like `local` or `development`),
take a look at [sven/env-providers](https://github.com/svenluijten/env-providers).

## Usage
If you now run `php artisan` you can see two new commands:
- `make:view`
- `scrap:view`

### Create a view
```bash
# Create a view 'index.blade.php' in the default directory
$ php artisan make:view index

# Create a view 'index.blade.php' in a subdirectory ('pages')
$ php artisan make:view pages.index

# Create a view in a custom directory
$ php artisan make:view index --directory=custom/path

# Give the view a custom file extension
$ php artisan make:view index --extension=html

# Extend an existing view
$ php artisan make:view index --extends=app

# Add a section to the view
$ php artisan make:view index --section=content

# Add an inline section to the view
$ php artisan make:view index --section="title:Hello world"
# Remember to add quotes around the section if you want to use spaces

# Add 2 sections to the view
$ php artisan make:view index --sections=title,content

# Add one inline and one block-level section to the view
$ php artisan make:view index --sections="title:Hello world,content"
# Remember to add quotes around the sections if you want to use spaces

# Create a resource called 'products'
$ php artisan make:view products --resource

# Create a resource with only specific verbs
$ php artisan make:view products --resource --verbs=index,create,edit

# Create a resource that extends views and adds sections
$ php artisan make:view products --resource --extends=layout --sections=foo,bar

# Use the force flag to force the creation of the view
$ php artisan make:view index --force
# This will overwrite a view if it already exists
```

### Scrap a view
```bash
# Scrap the view 'index.blade.php'
$ php artisan scrap:view index

# Remove the view by dot notation
$ php artisan scrap:view pages.index
```

## Contributing
All contributions (pull requests, issues and feature requests) are
welcome. Make sure to read through the [CONTRIBUTING.md](CONTRIBUTING.md) first,
though. See the [contributors page](../../graphs/contributors) for all contributors.

## License
`sven/artisan-view` is licensed under the MIT License (MIT). Please see the
[license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sven/artisan-view.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-green.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sven/artisan-view.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/svenluijten/artisan-view.svg?style=flat-square
[ico-codeclimate]: https://img.shields.io/codeclimate/github/svenluijten/artisan-view.svg?style=flat-square
[ico-quality]: https://img.shields.io/scrutinizer/g/svenluijten/artisan-view.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/56054783/shield

[link-packagist]: https://packagist.org/packages/sven/artisan-view
[link-downloads]: https://packagist.org/packages/sven/artisan-view
[link-travis]: https://travis-ci.org/svenluijten/artisan-view
[link-codeclimate]: https://codeclimate.com/github/svenluijten/artisan-view
[link-quality]: https://scrutinizer-ci.com/g/svenluijten/artisan-view/?branch=master
[link-styleci]: https://styleci.io/repos/56054783
