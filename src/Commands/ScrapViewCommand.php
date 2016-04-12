<?php

namespace Sven\ArtisanView\Commands;

use Sven\ArtisanView\View;
use Illuminate\Console\Command;

class ScrapViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap:view {name : The view to remove.}
                                       {--directory=resources/views : Where are your views stored?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap an existing view';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $directory = (string) $this->option('directory');

        $view = new View(
            base_path($directory)
        );

        $name = (string) $this->argument('name');

        if ( ! $this->confirm("Are you sure you want to scrap the view [$name]?")) {
            return $this->info("Okay, no harm done!");
        }

        try {
            $view->scrap($name);

            return $this->info("View [$name] successfully scrapped.");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }
}
