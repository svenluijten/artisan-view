<?php

namespace Sven\ArtisanView;

use Illuminate\Support\ServiceProvider;

class ArtisanViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['make:view'] = $this->app->share(function () {
            return new Commands\MakeViewCommand();
        });

        $this->app['scrap:view'] = $this->app->share(function () {
            return new Commands\ScrapViewCommand();
        });

        $this->app['list:view'] = $this->app->share(function () {
            return new Commands\ListViewCommand();
        });

        $this->commands(
            'make:view',
            'scrap:view',
            'list:view'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
