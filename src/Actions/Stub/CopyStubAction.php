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
        $absolutePath = $data->targetPath . '/' . $data->fileName;

        $filesystem = new Filesystem;

        $filesystem->ensureDirectoryExists(path: $data->targetPath);

        $filesystem->copy(
            path: $data->stubPath,
            target: $absolutePath,
        );

        ReplacePlaceholderAction::resolve()
            ->execute($absolutePath, $data->placeholders);
    }
}
