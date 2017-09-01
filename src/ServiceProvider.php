<?php

namespace Sven\ArtisanView;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->commands([
            Commands\MakeView::class,
            Commands\ScrapView::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            Commands\MakeView::class,
            Commands\ScrapView::class,
        ];
    }
}
