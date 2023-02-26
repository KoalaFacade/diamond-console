<?php

namespace KoalaFacade\DiamondConsole\Commands\Infrastructure;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class ListenerMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'infrastructure:make:listener {name} {domain} {--event=} {--force}';

    protected $description = 'Create a new listener';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating file to our project');
    }

    public function afterCreate(): void
    {
        if ($this->resolveEventOption()) {
            Artisan::call(
                command: 'infrastructure:make:event ' . $this->resolveEventOption() . ' ' . $this->resolveDomainArgument()
            );
        }

        $this->info(string: 'Successfully generate base file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveInfrastructurePath(),
                domainArgument: $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Listeners',
            )
        );
    }

    public function getStubPath(): string
    {
        $stub = 'listener';

        if ($this->resolveEventOption()) {
            $stub .= '-event';
        }

        return Source::resolveStubForPath(name: $stub);
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
            event: $this->resolveEventOption(),
            eventNamespace: $this->resolveEventNamespace()
        );
    }

    public function resolveEventNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveInfrastructurePath(),
                domainArgument: $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Events\\' . $this->resolveEventOption(),
            )
        );
    }
}
