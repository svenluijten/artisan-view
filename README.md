![artisan-view](https://cloud.githubusercontent.com/assets/11269635/14457826/a3bde82a-00ad-11e6-8161-0c218937156a.jpg)

# Artisan View

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-circleci]][link-circleci]
[![StyleCI][ico-styleci]][link-styleci]

This package adds a handful of view-related commands to Artisan in your Laravel
projects. Easily generate blade files that extend other views, automatically
scaffold out sections, and remove views. All from the command line!

## Index
- [Installation](#installation)
  - [Downloading](#downloading)
  - [Registering the service provider](#registering-the-service-provider)
- [Usage](#usage)
  - [Creating views](#creating-views)
  - [Nesting views](#nesting-views)
  - [Extending and sections](#extending-and-sections)
  - [REST resources](#rest-resources)
  - [Scrapping views](#scrapping-views)
- [Contributing](#contributing)
- [License](#license)

## Installation
You just have to follow a couple of simple steps to install this package.

### Downloading
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

### Registering the service provider
Next, add `Sven\ArtisanView\ServiceProvider::class` to your `providers` array in `config/app.php`:

```php
// config/app.php
'providers' => [
    ...
    Sven\ArtisanView\ServiceProvider::class,
];
```

If you want to only load this service provider in a specific environment (like `local` or `development`),
take a look at [sven/env-providers](https://github.com/svenluijten/env-providers).

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
