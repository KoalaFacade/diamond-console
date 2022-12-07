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
use KoalaFacade\DiamondConsole\Foundation\EvaluatesClosures;

class ResolveCommandAction extends Action
{
    use EvaluatesClosures;

    protected Closure | null $copyStubData = null;

    protected Console $console;

    /**
     * @param  Console  $console
     * @return void
     *
     * @throws FileAlreadyExistException
     * @throws FileNotFoundException
     */
    public function execute(Console $console): void
    {
        $this->setConsole($console);

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $console->getFileName(),
                    namespacePath: $console->getNamespacePath(),
                ),
                withForce: $console->resolveForceOption(),
            );

        CopyStubAction::resolve()
            ->execute(
                data: $this->evaluate($this->copyStubData) ?? $this->getDefaultCopyStubData()
            );
    }

    public function getCopyStubDataUsing(Closure | null $callback)
    {
        $this->copyStubData = $callback;
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

    protected function setConsole(Console $console): void
    {
        $this->console = $console;
    }
}
