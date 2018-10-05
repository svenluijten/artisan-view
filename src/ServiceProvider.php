<?php

namespace Sven\ArtisanView;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register(): void
    {
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
