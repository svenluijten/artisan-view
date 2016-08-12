<?php

namespace Sven\ArtisanView;

use Symfony\Component\Finder\Finder;

class ViewFactory
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * Instantiate the ViewFacory class.
     *
     * @param \Symfony\Component\Finder\Finder  $finder  Symfony's finder component
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function create($file)
    {

    }
}
