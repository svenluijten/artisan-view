<?php

namespace Sven\ArtisanView\Tests;

use Sven\ArtisanView\ServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

abstract class TestCase extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return ServiceProvider::class;
    }
}
