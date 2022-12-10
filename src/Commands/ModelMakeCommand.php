<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class ModelMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'domain:make:model {name} {domain} {--f|factory} {--m|migration} {--force}';

    protected $description = 'Create a new model';

    protected function resolveFactoryNameSuffix(): string
    {
        return Str::finish(Str::ucfirst($this->resolveNameArgument()), cap: 'Factory');
    }

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating model file to your project');
    }

    public function afterCreate(): void
    {
        if ($this->resolveMigrationOption()) {
            Artisan::call(
                command: Str::of('application:migration Create[name]Table --create=[create]')
                    ->replace(
                        search: '[name]',
                        replace: Str::pluralStudly($this->resolveNameArgument())
                    )
                    ->replace(
                        search: '[create]',
                        replace: $this->resolveNameArgument()
                    )
                    ->toString()
            );
        }

        if ($this->resolveFactoryOption()) {
            Artisan::call(
                command: 'infrastructure:make:factory',
                parameters: [
                    'name' => $this->resolveFactoryNameSuffix(),
                    'domain' => $this->resolveDomainArgument(),
                ]
            );
        }

        $this->info(string: 'Successfully generate model file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveDomainPath(),
                domainArgument: 'Shared\\' . $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Models',
            )
        );
    }

    public function getStubPath(): string
    {
        return $this->resolveFactoryOption()
            ? Source::resolveStubForPath(name: 'model-factory')
            : Source::resolveStubForPath(name: 'model');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
            factoryContract: $this->resolveFactoryNameSuffix(),
            factoryContractNamespace: Source::resolveNamespace(
                data: new NamespaceData(
                    structures: Source::resolveDomainPath(),
                    domainArgument: 'Shared',
                    nameArgument: $this->resolveNameArgument(),
                    endsWith: 'Contracts\\Database\\Factories',
                )
            )
        );
    }

    protected function resolveMigrationOption(): bool
    {
        return (bool) $this->option(key: 'migration');
    }
}
