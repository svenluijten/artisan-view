<?php

namespace Sven\ArtisanView;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    public function register(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Commands\MakeView::class,
            Commands\ScrapView::class,
        ]);
    }

    public function provides(): array
    {
        return [
            Commands\MakeView::class,
            Commands\ScrapView::class,
        ];
    }
}
