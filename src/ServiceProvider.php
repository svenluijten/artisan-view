<?php

namespace Sven\ArtisanView;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{
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
