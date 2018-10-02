<?php

namespace Sven\ArtisanView;

class Destroyer extends ViewActor
{
    /**
     * Destroy the configured view(s).
     */
    public function destroy()
    {
        $views = $this->getViews();

        $this->destroyViews(
            $this->getViewNames($views)
        );
    }

    /**
     * @param array $names
     */
    protected function destroyViews(array $names)
    {
        foreach ($names as $name) {
            $path = PathHelper::normalizePath(
                $this->config->getPath().DIRECTORY_SEPARATOR.$name
            );

            unlink($path);

            $this->removeParentDirectory($path);
        }
    }

    /**
     * @param string $path
     */
    protected function removeParentDirectory($path)
    {
        $folderName = PathHelper::removeFileName($path);

        if (count(scandir($folderName)) === 2) {
            rmdir($folderName);
        }
    }
}
