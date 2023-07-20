<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Str;

class Config
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var bool
     */
    protected $resource = false;

    /**
     * @var array
     */
    protected $verbs = ['index', 'create', 'edit', 'show', 'delete'];

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $generate;

    /**
     * @var string
     */
    protected $ui;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Config
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return Config
     */
    public function setExtension($extension)
    {
        if (!Str::startsWith($extension, '.')) {
            $extension = ".$extension";
        }

        $this->extension = $extension;

        return $this;
    }

    /**
     * @return bool
     */
    public function isResource()
    {
        return $this->resource;
    }

    /**
     * @param bool $resource
     * @return Config
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return array
     */
    public function getVerbs()
    {
        return $this->verbs;
    }

    /**
     * @param mixed ...$verbs
     * @return Config
     */
    public function setVerbs(...$verbs)
    {
        $this->verbs = $verbs;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForce()
    {
        return $this->force;
    }

    /**
     * @param bool $force
     * @return Config
     */
    public function setForce(bool $force)
    {
        $this->force = $force;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getGenerate()
    {
        return $this->generate;
    }

    public function setGenerate($generate)
    {
        $this->generate = $generate;

        return $this;
    }

    public function getUi()
    {
        return $this->ui;
    }

    public function setUi($ui)
    {
        $this->ui = $ui;

        return $this;
    }
}
