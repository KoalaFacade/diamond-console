<?php

namespace KoalaFacade\DiamondConsole\Actions\Command;

use Closure;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;

readonly class ResolveCommandAction extends Action
{
    public function __construct(
        protected Console $console,
        protected Closure | null $copyStubData = null,
    ) {
    }

    /**
     * @return void
     *
     * @throws FileAlreadyExistException
     * @throws FileNotFoundException
     */
    public function execute(): void
    {
        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $this->console->getFileName(),
                    namespacePath: $this->console->getNamespacePath(),
                ),
                withForce: $this->console->resolveForceOption(),
            );

        CopyStubAction::resolve()->execute(data: $this->getDefaultCopyStubData());
    }

    protected function getDefaultCopyStubData(): CopyStubData
    {
        return new CopyStubData(
            stubPath: $this->console->getStubPath(),
            targetPath: $this->console->getNamespacePath(),
            fileName: $this->console->getFileName(),
            placeholders: $this->console->resolvePlaceholders()
        );
    }
}
