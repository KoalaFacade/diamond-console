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

class MakeFactoryCommand extends Command
{
    use InteractsWithDDD, HasArguments, HasOptions;

    protected $signature = 'infrastructure:make:factory {name} {domain} {--force}';

    protected $description = 'Create a model Factory';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating factory & interface file to your project');

        $this->resolveFactoryContract();

        $this->resolveFactoryConcrete();
    }

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    protected function resolveFactoryContract(): void
    {
        $fileName = $this->resolveFactoryFileName();

        $namespace = $this->resolveFactoryContractNamespace();

        $namespacePath = $this->resolveNamespacePath(namespace: $namespace);

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    namespacePath: $namespacePath,
                ),
                withForce: $this->resolveForceOption()
            );

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveNameArgument()
        );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolveStubForPath(name: 'factory-contract'),
                    namespacePath: $namespacePath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                )
            );

        $this->info(string: 'Succeed generate Factory Interface at ' . $namespacePath . '/' . $fileName);
    }

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    protected function resolveFactoryConcrete(): void
    {
        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            structures: $this->resolveInfrastructurePath(),
            suffix: 'Factories',
            prefix: $this->resolveDomainArgument() . '\\Database'
        );

        $namespacePath = $this->resolveNamespacePath(namespace: $namespace);

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    namespacePath: $namespacePath,
                ),
                withForce: $this->resolveForceOption()
            );

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveNameArgument(),
            factoryContract:  $this->resolveNameFromPhp(name: $this->resolveFactoryFileName()),
            factoryContractNamespace: $this->resolveFactoryContractNamespace()
        );

        CopyStubAction::resolve()
            ->execute(
                new CopyStubData(
                    stubPath: $this->resolveStubForPath(name: 'factory'),
                    namespacePath: $namespacePath,
                    fileName: $fileName,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Succeed generate Factory concrete at ' . $namespacePath . '/' . $fileName);
    }

    protected function resolveFactoryContractNamespace(): string
    {
        return $this->resolveNamespace(
            structures: $this->resolveDomainPath(),
            suffix: 'Contracts\\Database\\Factories',
            prefix: 'Shared',
        );
    }

    protected function resolveFactoryFileName(): string
    {
        return $this->resolveNameArgument() . '.php';
    }
}
