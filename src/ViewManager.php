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

    public function create(string $view, bool $force = false): bool
    {
        foreach ($this->getFileNames($view) as $filename) {
            $fullPath = $this->config->getLocation().DIRECTORY_SEPARATOR.$filename;

            // 2. Build up the contents of the view.

            $this->filesystem->makeDirectory(
                $this->everythingBeforeLast($fullPath, '/'), 0755, true, true
            );

            $this->filesystem->put($fullPath, '');
        }

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

    public function delete(string $view): bool
    {
        // 1. Get the full path + name of the view(s) to delete.
        // 2. Remove the view(s) from step #1.
        // 3. Remove the folder the view(s) is / were in if it is empty.

        return true;
    }

    protected function everythingBeforeLast(string $haystack, string $search): string
    {
        return substr($haystack, 0, strrpos($haystack, $search));
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
