<?php

namespace KoalaFacade\DiamondConsole\Commands\Infrastructure;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class ObserverMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'infrastructure:make:observer {name} {domain} {--force}';

    protected $description = 'Create a new observer';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating file to our project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate observer file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                domainArgument: $this->resolveDomainArgument(),
                structures: Source::resolveInfrastructurePath(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Database\\Observers',
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'infrastructure/observer');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
        );
    }
}
