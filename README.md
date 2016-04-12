![package](https://cloud.githubusercontent.com/assets/11269635/14457826/a3bde82a-00ad-11e6-8161-0c218937156a.jpg)

# Package

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Code Climate][ico-codeclimate]][link-codeclimate]
[![Code Quality][ico-quality]][link-quality]
[![SensioLabs Insight][ico-insight]][link-insight]

Short description of the package. What does it do and why should people download
it? Brag a bit but don't exaggerate. Talk about what's to come and tease small
pieces of functionality.

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

Next, add the `ArtisanViewServiceProvider` to your `providers` array in `config/app.php`:

```php
// config/app.php
'providers' => [
    ...
    Sven\ArtisanView\ArtisanViewServiceProvider::class,
];
```

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

# Add 2 sections to the view
$ php artisan make:view index --sections=title,content

# Create a resource with a name of 'products'
$ php artisan make:view products --resource

# Create a resource with only specific verbs
$ php artisan make:view products --resource --verbs=index,create,edit
```

### Scrap a view
```bash
# Scrap the view 'index.blade.php'
$ php artisan scrap:view index

# Remove the view by dot notation
$ php artisan scrap:view pages.index
```

## Contributing
All contributions (in the form on pull requests, issues and feature-requests) are
welcomed. See the [contributors page](../../graphs/contributors) for all contributors.

## License
`sven/artisan-view` is licenced under the MIT License (MIT). Please see the
[license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sven/artisan-view.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-green.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sven/artisan-view.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/svenluijten/artisan-view.svg?style=flat-square
[ico-codeclimate]: https://img.shields.io/codeclimate/github/svenluijten/artisan-view.svg?style=flat-square
[ico-quality]: https://img.shields.io/scrutinizer/g/svenluijten/artisan-view.svg?style=flat-square
[ico-insight]: https://img.shields.io/sensiolabs/i/insight-id.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sven/artisan-view
[link-downloads]: https://packagist.org/packages/sven/artisan-view
[link-travis]: https://travis-ci.org/svenluijten/artisan-view
[link-codeclimate]: https://codeclimate.com/github/svenluijten/artisan-view
[link-quality]: https://scrutinizer-ci.com/g/svenluijten/artisan-view/?branch=master
[link-insight]: https://insight.sensiolabs.com/projects/insight-id
