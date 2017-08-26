<?php

namespace Sven\ArtisanView\Tests;

use Illuminate\View\Factory;
use Sven\ArtisanView\ServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

abstract class TestCase extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    /**
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * Set up the testing environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->view = $this->app->make(Factory::class);
    }

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
