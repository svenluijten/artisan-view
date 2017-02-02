<?php

namespace Sven\ArtisanView;

use Illuminate\Support\ServiceProvider;

class ArtisanViewServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Commands\MakeViewCommand::class,
            Commands\ScrapViewCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Commands\MakeViewCommand::class,
            Commands\ScrapViewCommand::class,
        ];
    }
}
