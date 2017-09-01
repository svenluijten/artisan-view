<?php

namespace Sven\ArtisanView;

abstract class ViewActor
{
    /**
     * @var \Sven\ArtisanView\Config
     */
    protected $config;

    /**
     * ViewActor constructor.
     *
     * @param \Sven\ArtisanView\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    protected function getViews()
    {
        if (! $this->config->isResource()) {
            return [$this->config->getName()];
        }

        return array_map(function ($view) {
            return $this->config->getName().'.'.$view;
        }, $this->config->getVerbs());
    }

    /**
     * @param iterable $names
     *
     * @return array
     */
    protected function getViewNames(iterable $names)
    {
        return array_map(function ($name) {
            $name = str_replace('.', '/', $name);

            return $name.$this->config->getExtension();
        }, $names);
    }
}
