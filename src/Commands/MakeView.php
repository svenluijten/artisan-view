<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\BlockStack;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeView extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'make:view';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new view';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $path = $this->getPath();

        $generator = new Generator($this->getConfig(), $path);

        $generator->generate(
            (new BlockStack)->build($this->input, $path)
        );

        $this->info('View created successfully.');
    }

    /**
     * @return \Sven\ArtisanView\Config
     */
    private function getConfig()
    {
        return (new Config)
            ->setName($this->argument('name'))
            ->setExtension($this->option('extension'))
            ->setResource($this->option('resource'))
            ->setVerbs(...$this->option('verb'));
    }

    private function getPath()
    {
        $paths = app('view.finder')->getPaths();

        if (count($paths) === 1) {
            return head($paths);
        }

        return $this->choice('Where do you want to create the view(s)?', $paths, head($paths));
    }

    /**
     * {@inheritdoc}
     */
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
