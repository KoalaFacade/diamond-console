<?php

namespace KoalaFacade\DiamondConsole\Actions\Stub;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\Foundation\Action;

class CopyStubAction extends Action
{
    /**
     * Copy Stub to Diamond Infrastructure
     *
     * @param  CopyStubData  $data
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function execute(CopyStubData $data): void
    {
        $filePath = $data->destinationPath . '/' . $data->fileName;

        $filesystem = new Filesystem;

        $filesystem->ensureDirectoryExists(path: $data->destinationPath);

        $filesystem->copy(
            path: $data->stubPath,
            target: $filePath,
        );

        ReplacePlaceholderAction::resolve()
            ->execute($filePath, $data->placeholders);
    }
}
