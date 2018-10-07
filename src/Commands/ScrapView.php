<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\ViewManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScrapView extends Command
{
    use Concerns\ChoosesPath;

    protected $name = 'scrap:view';

    protected $description = 'Scrap an existing view';

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;

        parent::__construct();
    }

    public function handle(): int
    {
        $manager = ViewManager::make($this->config());

        if ($this->option('force') || $this->confirm('Are you sure you want to remove that view / those views?')) {
            $manager->delete($this->argument('name'));

            return 0;
        }

        $this->info('Okay, nothing was changed.');

        return 0;
    }

    private function config(): Config
    {
        return Config::make()
            ->setExtension($this->option('extension'))
            ->setResource($this->option('resource'), $this->option('verbs'))
            ->setPath($this->path());
    }

    protected function pathQuestion(): string
    {
        return 'What path should the view be scrapped from?';
    }

    protected function possiblePaths(): array
    {
        return $this->config->get('view.paths', []);
    }

    protected function getOptions(): array
    {
        return [
            ['extension', null, InputOption::VALUE_REQUIRED, 'The extension of the view to scrap.', 'blade.php'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Whether or not a RESTful resource should be remove.'],
            ['verb', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verb(s) to scrap views for.', ['index', 'show', 'create', 'edit']],
            ['force', null, InputOption::VALUE_NONE, 'Don\'t ask for confirmation before removing the view.'],
        ];
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to scrap.'],
        ];
    }
}
