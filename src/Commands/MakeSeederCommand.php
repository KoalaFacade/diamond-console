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

class MakeSeederCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

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
            identifier: 'Seeders',
            domain: $this->resolveDomainArgument() . '\\Database',
            layer: 'infrastructure'
        );

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveClassNameByFile(name: $fileName)
        );

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    destinationPath: $destinationPath
                ),
                withForce: $this->resolveForceOption()
            );

        CopyStubAction::resolve()->execute(
            data: new CopyStubData(
                stubPath: $this->resolvePathForStub(name: 'seeder'),
                destinationPath: $destinationPath,
                fileName: $fileName,
                placeholders: $placeholders
            )
        );

        $this->info(string: 'Success generate seeder file at ' . $destinationPath);
    }
}
