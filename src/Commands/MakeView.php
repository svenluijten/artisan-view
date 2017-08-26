<?php

namespace Sven\ArtisanView\Commands;

use Sven\ArtisanView\Blocks\Section;
use Sven\ArtisanView\Config;
use Illuminate\Console\Command;
use Sven\ArtisanView\Generator;
use Sven\ArtisanView\Blocks\Extend;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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

    public function handle()
    {
        $generator = new Generator($this->getConfig(), $this->buildBlockStack());

        $generator->generate();
    }

    /**
     * @return \Sven\ArtisanView\Config
     */
    private function getConfig()
    {
        return (new Config)
            ->setName($this->argument('name'))
            ->setExtension($this->option('extension'))
            ->setResource($this->option('resource'));
    }

    /**
     * @return array
     */
    protected function buildBlockStack()
    {
        $blocks = [];

        if ($this->option('extends')) {
            $blocks[] = new Extend($this->option('extends'));
        }

        foreach ((array) $this->option('section') as $section) {
            $blocks[] = new Section($section);
        }

        return $blocks;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extension', null, InputOption::VALUE_OPTIONAL, 'The extension of the generated view.', 'blade.php'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Whether or not a RESTful resource should be created.'],
            ['verbs', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verbs to generate views for.', ['index', 'show', 'create', 'edit']],
            ['section', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'A list of "@section"s to define in the created view(s).'],
            ['extends', null, InputOption::VALUE_OPTIONAL, 'The view to "@extend" from the created view(s).'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to create.'],
        ];
    }
}
