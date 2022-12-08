<?php

namespace KoalaFacade\DiamondConsole\Actions\Filesystem;

use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;

readonly class FilePresentAction extends Action
{
    /**
     * @throws FileAlreadyExistException
     */
    public function execute(FilePresentData $data, bool $withForce = false): bool
    {
        $filesystem = new Filesystem;

        $path = $data->namespacePath . '/' . $data->fileName;

        if ($withForce) {
            return $filesystem->delete(paths: $path);
        }

        if ($filesystem->exists(path: $path)) {
            return throw new FileAlreadyExistException($data->fileName);
        }

        return false;
    }
}
