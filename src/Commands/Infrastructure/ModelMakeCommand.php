<?php

namespace KoalaFacade\DiamondConsole\Commands\Infrastructure;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Model\ModelContractMakeAction;
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

    protected $signature = 'infrastructure:make:model {name} {domain} {--f|factory} {--m|migration} {--force}';

    protected $description = 'Create a new model';

    protected Console $modelContractMakeAction;

    protected function resolveFactoryNameSuffix(): string
    {
        return Str::finish($this->resolveNameArgument(), cap: 'Factory');
    }

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating model file to your project');

        $this->resolveModelContractConsole(
            console: ModelContractMakeAction::resolve(parameters: ['console' => $this])->execute()
        );
    }

    public function afterCreate(): void
    {
        if ($this->resolveMigrationOption()) {
            Artisan::call(
                command: Str::of('diamond:make:migration Create[name]Table --create=[create]')
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
                    '--model' => $this->getClassName(),
                ]
            );
        }

        $this->info(string: 'Successfully generate model file');
    }

    public function resolveModelContractConsole(Console $console): void
    {
        $this->modelContractMakeAction = $console;
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                domainArgument: $this->resolveDomainArgument(),
                structures: Source::resolveInfrastructurePath(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Database\\Models',
            )
        );
    }

    public function getStubPath(): string
    {
        return $this->resolveFactoryOption()
            ? Source::resolveStubForPath(name: 'infrastructure/model-factory')
            : Source::resolveStubForPath(name: 'infrastructure/model');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
            factoryContract: $this->resolveDomainArgument(),
            factoryContractNamespace: Source::resolveNamespace(
                data: new NamespaceData(
                    domainArgument: $this->resolveDomainArgument(),
                    structures: Source::resolveInfrastructurePath(),
                    nameArgument: $this->resolveNameArgument(),
                    endsWith: 'Database\\Factories'
                )
            ),
            factoryContractAlias: $this->resolveFactoryNameSuffix()
        );
    }

    protected function resolveMigrationOption(): bool
    {
        return (bool) $this->option(key: 'migration');
    }
}
