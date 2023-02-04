<?php

namespace KoalaFacade\DiamondConsole\Commands\Application;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Commands\Application\Concerns\InteractsWithConsoleInApplication;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class RequestMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsoleInApplication;

    protected $signature = 'application:make:request {name} {domain} {--force}';

    protected $description = 'Create a new request';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating request file to your project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate request file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveApplicationPath() . '\\Http',
                domainArgument: 'Requests\\' . $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'request');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: Str::ucfirst(string: $this->getNamespace()),
            class: $this->getClassName(),
        );
    }
}
