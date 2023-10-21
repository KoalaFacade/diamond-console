<?php

namespace KoalaFacade\DiamondConsole\Commands\Application;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class RequestMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'application:make:request {name} {domain} {--force}';

    protected $description = 'Create a new Request';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating action file to your project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate action file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                domainArgument: $this->resolveDomainArgument(),
                structures: Source::resolveApplicationPath(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Requests',
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'application/request');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
        );
    }
}
