<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\ViewGenerator;

class MakeView extends Command
{
    /**
     * @var string Name and signature of the command.
     */
    protected $signature = 'make:view
                          { name : The name of the view to create. }
                          { --extension=blade.php : The file-extension to be used. }';

    /**
     * @var string Command description.
     */
    protected $description = 'Create a new view';

    /**
     * @var ViewGenerator
     */
    protected $viewGenerator;

    /**
     * MakeView constructor.
     *
     * @param ViewGenerator $viewGenerator
     */
    public function __construct(ViewGenerator $viewGenerator)
    {
        parent::__construct();

        $this->viewGenerator = $viewGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return  mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $extension = $this->option('extension');

        $this->viewGenerator->make($name, $extension)
            ->build()
        ;
    }
}
