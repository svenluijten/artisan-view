<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Blocks\InlineSection;
use Sven\ArtisanView\Blocks\Section;
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
        $generator = new Generator($this->getConfig());

        $generator->generate($this->buildBlockStack());

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
            if (str_contains($section, ':')) {
                list($name, $title) = explode(':', $section);

                $blocks[] = new InlineSection($name, $title);
            } else {
                $blocks[] = new Section($section);
            }
        }

        return $blocks;
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
