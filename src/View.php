<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Sven\ArtisanView\Shared\ViewHelper;

class View
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $recent;

    /**
     * @var \Sven\ArtisanView\Shared\ViewHelper
     */
    private $helper;

    /**
     * Instantiate the FileInteractor.
     *
     * @param string $path  Base path where your views are located.
     * @param bool   $force Force the creation if file already exists.
     */
    public function __construct($path, $force = false)
    {
        $this->basePath = realpath($path);
        $this->helper = new ViewHelper($path, $force);
        $this->recent = new Collection();
    }

    /**
     * Create a new view file.
     *
     * @param string $name      The name of the view to create.
     * @param string $extension The extension to give the view.
     *
     * @return \Sven\ArtisanView\View
     */
    public function create($name, $extension = '.blade.php')
    {
        $this->helper->getPathFor($name);

        $fullPath = $this->helper->addExtension($extension);

        $this->helper->makeFile($fullPath);

        $this->addToRecent($fullPath);

        return $this;
    }

    /**
     * Extend a view.
     *
     * @param string $name The view to extend.
     *
     * @return \Sven\ArtisanView\View
     */
    public function extend($name)
    {
        if (empty($name)) {
            return $this;
        }

        $this->recent->each(function ($item) use ($name) {
            $stub = $this->getStub('extend', [$name]);

            $this->helper->appendTo($item, $stub);
        });

        return $this;
    }

    /**
     * Add sections to the recently created views.
     *
     * @param mixed $sections The sections to add.
     *
     * @return \Sven\ArtisanView\View
     */
    public function sections($sections)
    {
        if (empty($sections)) {
            return $this;
        }

        $sections = $this->helper->normalizeToArray($sections, ',');

        foreach ($sections as $section) {
            $parts = $this->helper->normalizeToArray($section, ':');

            $this->section(
                $parts[0],
                isset($parts[1]) ? $parts[1] : null
            );
        }

        return $this;
    }

    /**
     * Create a resource of views.
     *
     * @param string $name      Name of the resource.
     * @param mixed  $verbs     Verbs to create views for.
     * @param string $extension Extension of the views.
     *
     * @return \Sven\ArtisanView\View
     */
    public function resource($name, $verbs = null, $extension = '.blade.php')
    {
        $types = ['index', 'show', 'edit', 'create'];

        if (! is_null($verbs)) {
            $types = $this->helper->normalizeToArray($verbs, ',');
        }

        foreach ($types as $type) {
            $this->clean()->create("$name.$type", $extension);
        }

        return $this;
    }

    /**
     * Remove a view from the filesystem.
     *
     * @param string $name      The name of the view to remove.
     * @param string $extension Extension of the view to remove.
     *
     * @return void
     */
    public function scrap($name, $extension = '.blade.php')
    {
        $file = $this->helper->getPathFor($name).$this->helper->parseExtension($extension);
        $this->helper->removeFile($file);

        if ($directory = $this->shouldRemoveDirectory($file)) {
            $this->removeDirectory($directory);
        }
    }

    /**
     * Push an item to recent items.
     *
     * @param string $path Path to push to the recent items.
     *
     * @return void
     */
    private function addToRecent($path)
    {
        $this->recent->push($path);
    }

    /**
     * Get a stub by name and replace optional parameters.
     *
     * @param string $name   Name of the stub.
     * @param array  $params Parameters to replace in the stub.
     *
     * @return string Contents of the stub.
     */
    private function getStub($name, $params = [])
    {
        $stub = file_get_contents(__DIR__.'/stubs/'.$name.'.stub');

        foreach ($params as $param) {
            $stub = Str::replaceFirst('*', $param, $stub);
        }

        return $stub;
    }

    /**
     * Add a section to all recently created views.
     *
     * @param string $name    The name of the section.
     * @param string $content The content of the section.
     *
     * @return \Sven\ArtisanView\View
     */
    private function section($name, $content = null)
    {
        $this->recent->each(function ($item) use ($name, $content) {
            $stub = $this->getStub('section', [$name]);

            if (! is_null($content)) {
                $stub = $this->getStub('inline-section', [$name, $content]);
            }

            $this->helper->appendTo($item, $stub);
        });

        return $this;
    }

    /**
     * Reset the path back to its original value.
     *
     * @return \Sven\ArtisanView\View
     */
    private function clean()
    {
        $this->helper->setPath($this->basePath);

        return $this;
    }

    /**
     * @param string $path passed from scrapView, $dir passed from removeDir.
     *
     * @return bool|string false|parent directory of the path specified.
     */
    private function shouldRemoveDirectory($path)
    {
        $directory = substr($path, 0, strlen($path) - strlen(strrchr($path, '/')));

        if ($directory == $this->basePath) {
            return false;
        }

        if (is_readable($directory) && count(scandir($directory)) == 2) {
            return $directory;
        }

        return false;
    }

    /**
     * Remove the specified directory and any empty parent directories.
     *
     * @param string $directory The directory to be removed.
     */
    private function removeDirectory($directory)
    {
        if (is_dir($directory) && is_writable($directory)) {
            rmdir($directory);

            if ($parent = $this->shouldRemoveDirectory($directory)) {
                $this->removeDirectory($parent);
            }
        }
    }
}
