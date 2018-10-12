<?php

namespace Sven\ArtisanView;

class ViewManager
{
    /**
     * @var \Sven\ArtisanView\Config
     */
    protected $config;

    private function __construct(Config $config)
    {
        $this->config = $config;
    }

    public static function make(Config $config): self
    {
        return new self($config);
    }

    public function create(string $view): bool
    {
        // 1. Get the full path + name of the view(s) to create.
        // 2. Build up the contents of the view.
        // 3. Create intermediate folders for the view to be created. (?)
        // 4. Put the contents from step #2 in the file.

        return true;
    }

    public function delete(string $view): bool
    {
        // 1. Get the full path + name of the view(s) to delete.
        // 2. Remove the view(s) from step #1.
        //    - Ignore the 'force' configuration option here. This is already taken care of by the command.
        // 3. Remove the folder the view(s) is / were in if it is empty.

        return true;
    }
}
