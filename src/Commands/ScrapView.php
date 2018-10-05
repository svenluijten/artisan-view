<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScrapView extends Command
{
    protected $name = 'scrap:view';

    protected $description = 'Scrap an existing view';

    /**
     * Execute the command.
     */
    public function handle()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        return [
            ['extension', null, InputOption::VALUE_REQUIRED, 'The extension of the view to scrap.', 'blade.php'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Whether or not a RESTful resource should be scrapped.'],
            ['verb', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verb(s) to scrap views for.', ['index', 'show', 'create', 'edit']],
            ['force', null, InputOption::VALUE_NONE, 'Don\'t ask for confirmation before scrapping the view.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to scrap.'],
        ];
    }
}
