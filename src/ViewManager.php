<?php

namespace Sven\ArtisanView;

use Illuminate\Filesystem\Filesystem;

class ViewManager
{
    /**
     * @var \Sven\ArtisanView\Config
     */
    protected $config;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    private function __construct(Config $config, Filesystem $filesystem)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    public static function make(Config $config, Filesystem $filesystem): self
    {
        return new self($config, $filesystem);
    }

    public function create(string $view): bool
    {
        $filename = $this->getFileName($view);

        $fullPath = $this->config->getLocation().DIRECTORY_SEPARATOR.$filename;

        // 2. Build up the contents of the view.

        $this->filesystem->makeDirectory(
            str_before($fullPath, $this->config->getExtension()), 0755, true
        );

        $this->filesystem->put($fullPath, '');

        return true;
    }

    protected function getFileName(string $view): string
    {
        return str_replace('.', DIRECTORY_SEPARATOR, $view).$this->config->getExtension();
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
