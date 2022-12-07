<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Factory\FactoryContractMakeAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Support\Source;

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
    public function beforeCreate(): void
    {
        $this->info(string: 'Generating factory & interface file to your project');

        $this->resolveFactoryContractConsole(
            console: FactoryContractMakeAction::resolve()->execute(console: $this)
        );
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Succeed generate Factory concrete at ' . $this->getFullPath());
    }

    public function resolveFactoryContractConsole(Console $console): void
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
        return Source::resolveStubForPath(name: 'factory');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            structures: Source::resolveInfrastructurePath(),
            prefix: $this->resolveDomainArgument() . '\\Database',
            suffix: 'Factories'
        );
    }

    public function getClassName(): string
    {
        return Str::finish(Str::ucfirst($this->resolveNameArgument()), cap: 'Factory');
    }
}
