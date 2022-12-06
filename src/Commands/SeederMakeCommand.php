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

class SeederMakeCommand extends Command
{
    use InteractsWithDDD, HasArguments, HasOptions;

    protected $signature = 'infrastructure:make:seeder {name} {domain} {--force}';

    protected $description = 'Create seeder file';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating seeder to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            structures: $this->resolveInfrastructurePath(),
            suffix: 'Seeders',
            prefix: $this->resolveDomainArgument() . '\\Database'
        );

        $destinationPath = $this->resolveNamespacePath(namespace: $namespace);

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveNameArgument()
        );

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    namespacePath: $destinationPath
                ),
                withForce: $this->resolveForceOption()
            );

        CopyStubAction::resolve()->execute(
            data: new CopyStubData(
                stubPath: $this->resolveStubForPath(name: 'seeder'),
                namespacePath: $destinationPath,
                fileName: $fileName,
                placeholders: $placeholders
            )
        );

        $this->info(string: 'Success generate seeder file at ' . $destinationPath);
    }
}
