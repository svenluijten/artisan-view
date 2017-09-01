<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Destroyer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScrapView extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'scrap:view';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Scrap an existing view';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $config = $this->getConfig();

        if (! $config->isForce() && ! $this->confirm('Are you sure you want to scrap the view?')) {
            $this->line('Alright, nothing happened.');

            return;
        }

        $destroyer = new Destroyer($config);

        $destroyer->destroy();
    }

    /**
     * @return \Sven\ArtisanView\Config
     */
    protected function getConfig()
    {
        return (new Config)
            ->setName($this->argument('name'))
            ->setExtension($this->option('extension'))
            ->setResource($this->option('resource'))
            ->setVerbs(...$this->option('verbs'))
            ->setForce($this->option('force'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Don\'t ask for confirmation before scrapping the view.'],
            ['extension', null, InputOption::VALUE_REQUIRED, 'The extension of the view to scrap.', 'blade.php'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Whether or not a RESTful resource should be scrapped.'],
            ['verbs', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verbs to scrap views for.', ['index', 'show', 'create', 'edit']],
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
