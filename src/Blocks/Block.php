<?php

namespace Sven\ArtisanView\Blocks;

abstract class Block
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $contents;

    /**
     * @param string $name
     * @param string $contents
     */
    public function __construct($name, $contents = '')
    {
        $this->name = $name;
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return string
     */
    abstract public function render();
}
