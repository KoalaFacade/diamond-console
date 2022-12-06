<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithDDD;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\Contracts\Middleware;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class MakeProviderCommand extends Command implements Console, Middleware
{
    use HasArguments, HasOptions, InteractsWithDDD;

    protected $signature = 'infrastructure:make:provider {name} {domain} {--force}';

    protected $description = 'Create a new service provider class';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->resolveMiddleware();

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->getStubPath(),
                    namespacePath: $this->getNamespacePath(),
                    fileName: $this->getFileName(),
                    placeholders: new PlaceholderData(
                        namespace: $this->getNamespace(),
                        class: $this->getClassName(),
                    )
                )
            );
    }

    public function getNamespacePath(): string
    {
        return $this->resolveNamespacePath(namespace: $this->getNamespace());
    }

    public function getNamespace(): string
    {
        return $this->resolveNamespace(
            structures: $this->resolveInfrastructurePath(),
            suffix: 'Providers',
            prefix: $this->resolveDomainArgument(),
        );
    }

    public function getFileName(): string
    {
        return $this->resolveNameArgument() . '.php';
    }

    public function getStubPath(): string
    {
        return $this->resolveStubForPath(name: 'provider');
    }

    public function getClassName(): string
    {
        return $this->resolveNameArgument();
    }

    /**
     * @throws FileAlreadyExistException
     */
    public function resolveMiddleware(): void
    {
        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $this->getFileName(),
                    namespacePath: $this->getNamespacePath(),
                ),
                withForce: $this->resolveForceOption(),
            );
    }
}
