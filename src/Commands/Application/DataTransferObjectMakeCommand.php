<?php

namespace KoalaFacade\DiamondConsole\Commands\Application;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Commands\Application\Concerns\InteractsWithConsoleInApplication;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\Support\Source;

class DataTransferObjectMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsoleInApplication;

    protected $signature = 'application:make:data-transfer-object {name} {domain} {--force}';

    protected $description = 'Create a new Data Transfer Object';

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveApplicationPath() . '\\DataTransferObjects',
                domainArgument: $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'data-transfer-object');
    }

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating data transfer object file to your project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate data transfer object file');
    }
}
