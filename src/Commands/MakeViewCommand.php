<?php

namespace Sven\ArtisanView\Commands;

use Sven\ArtisanView\View;
use Illuminate\Console\Command;

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
                           {--sections= : A list of sections to create.}
                           {--section= : A name of a section to create.}
                           {--directory=resources/views/ : The directory where your views are stored.}
                           {--extension=blade.php : What file extension should the view have?}
                           {--force : Force the creation if file already exists.}';

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
        $force = (bool) $this->option('force');

        $view = new View(base_path($directory), $force);

        $name = (string) $this->argument('name');
        $extension = (string) $this->option('extension');
        $extend = (string) $this->option('extends');
        $sections = $this->option('sections') ?: $this->option('section');
        $resource = (bool) $this->option('resource');
        $verbs = $this->option('verbs');

        try {
            if ($resource) {
                $view->resource($name, $verbs, $extension)
                     ->extend($extend)
                     ->sections($sections);

                return $this->info("Resource [$name] successfully created");
            }

            $view->create($name, $extension)->extend($extend)->sections($sections);

            return $this->info("View [$name] successfully created");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
