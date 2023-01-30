<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class ResourceMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'application:make:resource {name} {domain} {--model=} {--force}';

    protected $description = 'Create a new resource';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating resource file to your project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate resource file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveApplicationPath() . '\\Http',
                domainArgument: 'Resources\\' . $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
            )
        );
    }

    public function getStubPath(): string
    {
        $stub = 'resource';

        if ($this->resolveModelOption()) {
            $stub .= '-model';
        }

        return Source::resolveStubForPath(name: $stub);
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: Str::ucfirst(string: $this->getNamespace()),
            class: $this->getClassName(),
            model: $this->resolveModelOption(),
            modelNamespace: $this->resolveModelNamespace()
        );
    }

    public function getNamespacePath(): string
    {
        return base_path(
            path: Source::transformNamespaceToPath(namespace: $this->getNamespace())
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
