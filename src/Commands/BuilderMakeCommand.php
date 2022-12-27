<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class BuilderMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'domain:make:builder {name} {domain} {--model=} {--force}';

    protected $description = 'Create a new query builder';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating query builder file to your project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate query builder file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveDomainPath(),
                domainArgument: 'Shared\\' . $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Models\\Builders',
            )
        );
    }

    public function getStubPath(): string
    {
        $stub = 'builder';

        if ($this->resolveModelOption()) {
            $stub .= '-model';
        }

        return Source::resolveStubForPath(name: $stub);
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
            model: $this->resolveModelOption(),
            modelNamespace: $this->resolveModelNamespace()
        );
    }

    public function resolveModelNamespace(): string | null
    {
        if ($this->resolveModelOption()) {
            $namespace = Source::resolveNamespace(
                data: new NamespaceData(
                    structures: Source::resolveDomainPath(),
                    domainArgument: 'Shared\\' . $this->resolveDomainArgument(),
                    nameArgument: $this->resolveModelOption(),
                    endsWith: 'Models\\' . $this->resolveModelOption(),
                )
            );
        }

        return $namespace ?? null;
    }
}
