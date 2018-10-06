<?php

namespace Sven\ArtisanView\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Sven\ArtisanView\ServiceProvider;
use Sven\LaravelTestingUtils\InteractsWithViews;

abstract class TestCase extends AbstractPackageTestCase
{
    use ServiceProviderTrait;
    use InteractsWithViews;

    protected function getServiceProviderClass($app): string
    {
        return ServiceProvider::class;
    }
}
