<?php

namespace Sven\ArtisanView;

use Illuminate\Foundation\Application;
use Sven\ArtisanView\Commands\ScrapView;
use Sven\ArtisanView\Commands\MakeView;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->app->bind('view.factory', function () {
            return new ViewGenerator();
        });

        $this->app->bind('commands.make:view', function (Application $app) {
            return new MakeView($app->make('view.factory'));
        });

        $this->app->bind(function () {
            return new ScrapView($app->make('view.factory'));
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
