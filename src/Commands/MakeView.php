<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeView extends Command
{
    protected $name = 'make:view';

    protected $description = 'Create a new view';

    public function handle()
    {
        //
    }

    protected function getOptions()
    {
        return [
            ['extension', null, InputOption::VALUE_OPTIONAL, 'The extension of the generated view.', 'blade.php'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Whether or not a RESTful resource should be created.'],
            ['verb', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verb(s) to generate views for.', ['index', 'show', 'create', 'edit']],
            ['section', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'A list of "@section"s to define in the created view(s).'],
            ['extends', null, InputOption::VALUE_OPTIONAL, 'The view to "@extend" from the created view(s).'],
            ['with-yields', 'y', InputOption::VALUE_NONE, 'Whether or not to add all "@yield" sections from extended template (if "--extends" was provided)'],
            ['with-stacks', 's', InputOption::VALUE_NONE, 'Whether or not to add all "@stacks" from extended template as @push (if "--extends" was provided)'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to create.'],
        ];
    }
}
