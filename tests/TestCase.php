<?php

namespace Sven\ArtisanView\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Sven\ArtisanView\ServiceProvider;

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
