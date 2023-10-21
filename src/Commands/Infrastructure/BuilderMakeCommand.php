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

class BuilderMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'infrastructure:make:builder {name} {domain} {--model=} {--force}';

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
                domainArgument: $this->resolveDomainArgument(),
                structures: Source::resolveInfrastructurePath(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Database\\Models\\Builders',
            )
        );
    }

    public function getStubPath(): string
    {
        $stub = 'infrastructure/builder';

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

    public function resolveModelNamespace(): ?string
    {
        if ($this->resolveModelOption()) {
            $namespace = Source::resolveNamespace(
                data: new NamespaceData(
                    domainArgument: $this->resolveDomainArgument(),
                    structures: Source::resolveInfrastructurePath(),
                    nameArgument: $this->resolveModelOption(),
                    endsWith: 'Database\\Models',
                )
            );
        }

        return $namespace ?? null;
    }
}
