<?php

namespace Sven\ArtisanView;

use Exception;
use Sven\ArtisanView\Blocks\Block;
use Sven\ArtisanView\CRUD\Generate;
use Sven\ArtisanView\CRUD\ViewType;

class Generator extends ViewActor
{
    /**
     * @param BlockStack $blockStack
     * @throws Exception
     */
    public function generate(BlockStack $blockStack)
    {
        $views = $this->getViews();

        if ($this->isGenerated()) {
            foreach ($views as $view) {
                $view = explode('.', $view);;
                if(count($view) > 1) {
                    $verbs = [$view[1]];
                } else {
                    $verbs = $this->config->getVerbs();
                }

                $view = $view[0];

                foreach($verbs as $verb) {
                    switch ($verb) {
                        case 'index':
                            $type = ViewType::INDEX;
                            break;
                        case 'create':
                            $type = ViewType::CREATE;
                            break;
                        case 'edit':
                            $type = ViewType::EDIT;
                            break;
                        case 'show':
                            $type = ViewType::SHOW;
                            break;
                        case 'delete':
                            $type = ViewType::DELETE;
                            break;
                        default:
                            throw new Exception('Invalid view type');
                    }

                    Generate::viewType($view, $type, $this->config, $blockStack->all(), $this->config->getUi());
                }
            }

            return;
        }

        $this->makeViews(
            $this->getViewNames($views), $blockStack->all()
        );
    }

    /**
     * @param array $names
     * @param Block[] $blocks
     */
    protected function makeViews(array $names, array $blocks)
    {
        $contents = BlockBuilder::build($blocks);

        foreach ($names as $name) {
            $path = PathHelper::normalizePath($this->config->getPath() . DIRECTORY_SEPARATOR . $name);
            PathHelper::createIntermediateFolders($path);

            file_put_contents($path, $contents);
        }
    }
}
