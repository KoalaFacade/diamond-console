<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithPath;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class MakeDTOCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

    protected $signature = 'diamond:dto {name} {domain} {--force}';

    protected $description = 'create new dto';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating DTO file to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $placeholders = new PlaceholderData(
            namespace: $this->resolveNamespace(
                identifier: 'DataTransferObjects',
                domain: $this->resolveDomainArgument()
            ),
            class: $this->resolveClassNameByFile(name: $fileName),
        );

        $destinationPath = $this->resolveNamespaceTarget(namespace: (string) $placeholders->namespace);

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    destinationPath: $destinationPath,
                ),
                withForce: $this->resolveForceOption(),
            );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'dto'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Successfully generate DTO file');
    }
}
