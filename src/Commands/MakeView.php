<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\Blocks\Extend;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\Generator;

class MakeView extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature
        = 'make:view
                            { name : The name of the view to create. }
                            { --extension=blade.php : The extension of the generated view. }
                            { --resource : Whether or not a RESTful resource should be created. }
                            { --section=* }';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new view';

    /**
     *
     */
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

        if ($this->hasOption('extends')) {
            $blocks[] = new Extend($this->option('extends'));
        }

        return $blocks;
    }
}
