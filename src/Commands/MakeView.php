<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\ViewManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeView extends Command
{
    use Concerns\ChoosesPath;

    protected $name = 'make:view';

    protected $description = 'Create a new view';

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

        $manager->create($this->argument('name'));

        return 0;
    }

    private function config(): Config
    {
        return Config::make()
            ->setExtension($this->option('extension'))
            ->setResource($this->option('resource'), $this->option('verbs'))
            ->setForce($this->option('force'))
            ->setPath($this->path());
    }

    protected function pathQuestion(): string
    {
        return 'What path should the view be stored in?';
    }

    protected function possiblePaths(): array
    {
        return $this->config->get('view.paths', []);
    }

    protected function getOptions(): array
    {
        return [
            ['extension', null, InputOption::VALUE_OPTIONAL, 'The extension of the generated view.', 'blade.php'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Whether or not a RESTful resource should be created.'],
            ['verb', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The HTTP verb(s) to generate views for.', ['index', 'show', 'create', 'edit']],
            ['section', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The "@section" tags to pre-fill in the created view(s).'],
            ['extends', null, InputOption::VALUE_OPTIONAL, 'The view to "@extend" from the created view(s).'],
            ['with-yields', null, InputOption::VALUE_NONE, 'Whether or not to add all "@yield" sections from extended template (if "--extends" was provided)'],
            ['with-stacks', null, InputOption::VALUE_NONE, 'Whether or not to add all "@stacks" from extended template as @push (if "--extends" was provided)'],
        ];
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to create.'],
        ];
    }
}
