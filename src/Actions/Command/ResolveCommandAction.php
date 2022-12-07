<?php

namespace KoalaFacade\DiamondConsole\Actions\Command;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;

class ResolveCommandAction extends Action
{
    /**
     * @param  Console  $command
     * @return void
     *
     * @throws FileAlreadyExistException
     * @throws FileNotFoundException
     */
    public function execute(Console $command): void
    {
        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $command->getFileName(),
                    namespacePath: $command->getNamespacePath(),
                ),
                withForce: $command->resolveForceOption(),
            );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $command->getStubPath(),
                    namespacePath: $command->getNamespacePath(),
                    fileName: $command->getFileName(),
                    placeholders: $command->resolvePlaceholders()
                )
            );
    }
}
