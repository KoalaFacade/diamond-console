<?php

namespace KoalaFacade\DiamondConsole\Actions\Filesystem;

use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\Foundation\Action;

class FilePresentAction extends Action
{
    /**
     * @param  FilePresentData  $data
     * @param  bool  $withForce
     * @return bool
     */
    public function execute(FilePresentData $data, bool $withForce = false): bool
    {
        $filesystem = new Filesystem;

        $path = $data->destinationPath . '/' . $data->fileName;

        if ($withForce) {
            $filesystem->delete(paths: $path);
        }

        return $filesystem->exists(path: $path);
    }
}
