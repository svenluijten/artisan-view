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
    protected $verbs = ['index', 'create', 'edit', 'show'];

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $extension
     *
     * @return $this
     */
    public function setExtension($extension)
    {
        if (! Str::startsWith($extension, '.')) {
            $extension = ".$extension";
        }

        $this->extension = $extension;

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
     * @param bool $resource
     *
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

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
     * @param array $verbs
     *
     * @return $this
     */
    public function setVerbs($verbs)
    {
        $this->verbs = func_get_args();

        return $this;
    }

    /**
     * @return array
     */
    public function getVerbs()
    {
        return $this->verbs;
    }
}
