<?php

namespace Sven\ArtisanView;

use Sven\ArtisanView\Commands\ScrapView;
use Sven\ArtisanView\Commands\MakeView;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->app['make:view'] = $this->app->share(function () {
            return new MakeView();
        });

        $this->app['scrap:view'] = $this->app->share(function () {
            return new ScrapView();
        });

        $this->commands(
            'make:view',
            'scrap:view'
        );
    }

    public function register()
    {
    	//
    }
}
