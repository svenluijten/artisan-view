<?php

namespace Sven\ArtisanView;

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
     * @var bool
     */
    protected $force;

    /**
     * @var string
     */
    protected $path;

    public static function make(): self
    {
        return new self();
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

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

    public function setForce(bool $force): self
    {
        $this->force = $force;

        return $this;
    }

    public function isForce(): bool
    {
        return $this->force;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
