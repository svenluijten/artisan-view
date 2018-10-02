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

        (new Destroyer($config, $this->getPath()))->destroy();

        $this->info('View scrapped successfully.');
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
            ->setVerbs(...$this->option('verb'))
            ->setForce($this->option('force'));
    }

    private function getPath()
    {
        $paths = app('view.finder')->getPaths();

        if (count($paths) === 1) {
            return head($paths);
        }

        return $this->choice('Where is/are the view(s) you want to scrap?', $paths, head($paths));
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
            ['verb', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verb(s) to scrap views for.', ['index', 'show', 'create', 'edit']],
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
