<?php

namespace Sven\ArtisanView;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

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
        foreach ($this->getFileNames($view) as $filename) {
            $fullPath = $this->config->getLocation().DIRECTORY_SEPARATOR.$filename;

            $this->filesystem->makeDirectory(
                $this->filesystem->dirname($fullPath), 0755, true, true
            );

            $contents = BlockBuilder::make()->build($this->config);

            $this->filesystem->put($fullPath, $contents);
        }

        return true;
    }

    public function delete(string $view): bool
    {
        // 1. Get the full path + name of the view(s) to delete.
        // 2. Remove the view(s) from step #1.
        // 3. Remove the folder the view(s) is / were in if it is empty.

        return true;
    }

    protected function getFileNames(string $view): array
    {
        $viewPaths = str_replace(
            '.', DIRECTORY_SEPARATOR, $this->getViewNames($view)
        );

        return array_map(function ($viewName) {
            return $viewName.$this->config->getExtension();
        }, $viewPaths);
    }

    protected function getViewNames(string $view): array
    {
        if (! $this->config->isResource()) {
            return Arr::wrap($view);
        }

        return array_map(function ($verb) use ($view) {
            return $view.'.'.$verb;
        }, $this->config->getVerbs());
    }
}
