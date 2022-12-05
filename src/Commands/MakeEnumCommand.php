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

class MakeEnumCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

    protected $signature = 'domain:make:enum {name} {domain} {--force}';

    protected $description = 'Create a new enum';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating enum file to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            identifier: 'Enums',
            domain: $this->resolveDomainArgument()
        );

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveClassNameByFile(name: $fileName),
        );

        if (version_compare(PHP_VERSION, '8.1.0', '<=')) {
            $this->error('The required PHP version is 8.1 while the version you have is ' . PHP_VERSION);

            return;
        }

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
                    stubPath: $this->resolvePathForStub(name: 'enum'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                )
            );

        $this->info(string: 'Successfully generate enum file');
    }
}
