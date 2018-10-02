<?php

namespace Sven\ArtisanView\Voters;

use Sven\ArtisanView\Blocks\Push;
use Sven\ArtisanView\BlockStack;
use Sven\ArtisanView\PathHelper;
use Symfony\Component\Console\Input\InputInterface;

class StacksFromParent implements Voter
{
    /**
     * @var string
     */
    protected $path;

    /**
     * {@inheritdoc}
     */
    public function canHandle(InputInterface $input)
    {
        return $input->hasOption('extends') && $input->getOption('extends')
            && $input->hasOption('with-stacks') && $input->getOption('with-stacks');
    }

    public function inPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, BlockStack $blockStack)
    {
        preg_match_all(
            '~(?<!\@)\@stack\((\'|\")(.+)\1\)~',
            $this->file($input->getOption('extends')),
            $matches
        );

        foreach ($matches[2] as $stack) {
            $blockStack->add(new Push($stack));
        }
    }

    /**
     * Get the contents of one of the view with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function file($name)
    {
        $path = PathHelper::normalizePath(
            $this->path.DIRECTORY_SEPARATOR.str_replace('.', '/', $name).'.blade.php'
        );

        return file_exists($path) ? file_get_contents($path) : '';
    }
}
