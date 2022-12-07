<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Command\ResolveCommandAction;
use KoalaFacade\DiamondConsole\Actions\Factory\FactoryContractMakeAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Support\Component;

class FactoryMakeCommand extends Command implements Console
{
    use InteractsWithConsole, HasOptions, HasArguments;

    protected $signature = 'infrastructure:make:factory {name} {domain} {--force}';

    protected $description = 'Create a model Factory';

    protected Console $factoryContractMakeAction;

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating factory & interface file to your project');

        $this->resolveFactoryContractMake(
            console: FactoryContractMakeAction::resolve()->execute(command: $this)
        );

        ResolveCommandAction::resolve()->execute(command: $this);

        $this->info(string: 'Succeed generate Factory concrete at ' . $this->getNamespacePath() . '/' . $this->getFileName());
    }

    public function resolveFactoryContractMake(Console $console): void
    {
        $this->factoryContractMakeAction = $console;
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->resolveNameArgument(),
            factoryContract: $this->factoryContractMakeAction->getClassName(),
            factoryContractNamespace: $this->factoryContractMakeAction->getNamespace()
        );
    }

    public function getStubPath(): string
    {
        return Component::resolveStubForPath(name: 'factory');
    }

    public function getNamespace(): string
    {
        return Component::resolveNamespace(
            structures: Component::resolveInfrastructurePath(),
            suffix: 'Factories',
            prefix: $this->resolveDomainArgument() . '\\Database'
        );
    }
}
