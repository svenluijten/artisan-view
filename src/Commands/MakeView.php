<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use League\Flysystem\Filesystem;
use Sven\ArtisanView\ViewFactory;
use League\Flysystem\Adapter\Local;

class MakeView extends Command
{
    /**
     * @var  string  Name and signature of the command.
     */
    protected $signature = 'make:view
                          { name : The name of the view to create. }
                          { --extends= : Name of the view to extend. }
                          { --section= : Name of a section to add to the view. }
                          { --extension= : The file-extension to be used. }
                          { --resource : Do you want to create a RESTful resource? }
                          { --verbs= : The verbs that should be used for the resource. }
                          { --force : Force the creation of a view if one already exists. }
                          { --interactive : Create a view by answering some questions. }';

    /**
     * @var  array  Questions to be asked if the view is made interactively.
     */
    protected $questions = [
        'name' => [
            'question' => 'What is the name of the view to create?',
        ],
        'extends' => [
            'question' => 'What view should be extended?',
        ],
        'section' => [
            'question' => 'What sections should be added to the view?',
            'parent' => 'extends',
        ],
        'extension' => [
            'question' => 'Which file extension should the view have?',
            'default' => '.blade.php',
        ],
        'resource' => [
            'question' => 'Should a RESTful resource be created?',
            'default' => false,
        ],
        'verbs' => [
            'question' => 'What views should be in the resource?',
            'parent' => 'resource',
            'default' => 'index,show,create,edit',
        ],
    ];

    /**
     * @var  array  The answers already given.
     */
    protected $answers = [];

    /**
     * @var  string  Command description.
     */
    protected $description = 'Create a new view';

    /**
     * Execute the console command.
     *
     * @return  mixed
     */
    public function handle()
    {
        if ($this->option('interactive')) {
            return $this->buildViewInteractively();
        }

        return $this->buildView([
            'name' => $this->argument('name'),
            'extends' => $this->option('extends'),
            'section' => explode(',', $this->option('section')),
            'extension' => $this->option('extension'),
            'resource' => $this->option('resource'),
            'verbs' => explode(',', $this->option('verbs')),
        ]);
    }


    /**
     * Build up a new ViewFactory instance.
     *
     * @param $options
     */
    protected function buildView($options)
    {
        $driver = new Local(
            config('view.paths')[0]
        );

        $view = new ViewFactory(
            new Filesystem($driver)
        );

        $view = $view->create(
            $options['name'],
            $options['extension']
        );

        return $this->addStubsToView($view);
    }

    /**
     * Build up the view through a series of questions.
     *
     * @return  mixed
     */
    protected function buildViewInteractively()
    {
        foreach ($this->questions as $parameter => $options) {
            $this->askQuestion($parameter, $options);
        }

        $this->buildView($this->answers);
    }

    /**
     * Ask the question if the preceding question has been answered with a truthy value.
     *
     * @param  string  $parameter  Command parameter to ask the question for.
     * @param  array  $options  Information about the question to ask.
     *
     * @return  void
     */
    protected function askQuestion($parameter, array $options)
    {
        $parentName = isset($options['parent']) ? $options['parent'] : false;

        $parent = ($parentName && isset($this->answers[$parentName])) ? $this->answers[$parentName] : false;

        if (!$parent) return;

        $this->answers[$parameter] = $this->ask(
            sprintf('%s [%s]', $options['question'], $options['default'])
        );
    }

    protected function addStubsToView(ViewFactory $view)
    {

    }
}
