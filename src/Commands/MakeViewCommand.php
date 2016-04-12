<?php

namespace Sven\Moretisan\Commands;

use Sven\ArtisanView\View;
use Illuminate\Console\Command;

// use Sven\Moretisan\Components\MakeView\MakeView;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view
                           {name : The name of the view to create.}
                           {--resource : Should we create a RESTful resource?}
                           {--verbs= : The verbs that should be used for the resource.}
                           {--extends= : What \'master\' view should be extended?}
                           {--sections= : A comma-separated list of sections to create.}
                           {--directory=resources/views/ : The directory where your views are stored.}
                           {--extension=blade.php : What file extension should the view have?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $directory = (string) $this->option('directory');

        $view = new View(base_path($directory));

        $name      = (string) $this->argument('name');
        $extension = (string) $this->option('extension');
        $extend    = (string) $this->option('extends');
        $sections  = $this->option('sections');
        $resource  = (string) $this->option('resource');
        $verbs     = $this->option('verbs');

        try {
            if ($resource) {
                $view->resource($name, $verbs, $extension);

                return $this->info("Resource [$name] successfully created");
            }

            $view->create($name, $extension)->extend($extend)->sections($sections);

            return $this->info("View [$name] successfully created");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
