<?php

namespace Sven\ArtisanView;

use Illuminate\Support\Str;

class Config
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * @var bool
     */
    protected $isResource;

    /**
     * @var array
     */
    protected $verbs;

    /**
     * @var string
     */
    protected $location;

    public static function make(): self
    {
        return new self();
    }

    public function setExtension(string $extension): self
    {
        $this->extension = Str::start($extension, '.');

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setResource(bool $isResource, array $verbs): self
    {
        $this->isResource = $isResource;
        $this->verbs = $verbs;

        return $this;
    }

    public function isResource(): bool
    {
        return $this->isResource;
    }

    public function getVerbs(): array
    {
        return $this->verbs;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
