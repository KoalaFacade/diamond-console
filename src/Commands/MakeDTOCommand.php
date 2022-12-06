<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithDDD;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class MakeDTOCommand extends Command
{
    use InteractsWithDDD, HasArguments, HasOptions;

    protected $signature = 'domain:make:dto {name} {domain} {--force}';

    protected $description = 'Create a new dto';

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
                structures: $this->resolveDomainPath(),
                suffix: 'DataTransferObjects',
                prefix: $this->resolveDomainArgument()
            ),
            class: $this->resolveNameFromPhp(name: $fileName),
        );

        $destinationPath = $this->resolveNamespacePath(namespace: (string) $placeholders->namespace);

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    namespacePath: $destinationPath,
                ),
                withForce: $this->resolveForceOption(),
            );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolveStubForPath(name: 'dto'),
                    namespacePath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Successfully generate DTO file');
    }
}
