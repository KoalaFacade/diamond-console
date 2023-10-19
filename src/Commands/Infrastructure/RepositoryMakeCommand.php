<?php

namespace KoalaFacade\DiamondConsole\Commands\Infrastructure;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Actions\Repository\RepositoryContractMakeAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class RepositoryMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'infrastructure:make:repository {name} {domain} {--force}';

    protected $description = 'Create a new repository';

    protected Console $repositoryContractMakeAction;

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating model file to your project');

        $this->resolveRepositoryContractConsole(
            console: RepositoryContractMakeAction::resolve(parameters: ['console' => $this])->execute()
        );
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Succeed generate Repository concrete at ' . $this->getFullPath());
    }

    public function resolveRepositoryContractConsole(Console $console): void
    {
        $this->repositoryContractMakeAction = $console;
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                domainArgument: $this->resolveDomainArgument(),
                structures: Source::resolveInfrastructurePath(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Repositories',
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'infrastructure/repository');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
            contractName: $this->resolveDomainArgument(),
            contractNamespace: Source::resolveNamespace(
                data: new NamespaceData(
                    domainArgument: 'Shared',
                    structures: $this->resolveDomainArgument(),
                    nameArgument: $this->resolveNameArgument(),
                    endsWith: 'Contracts\\Repositories'
                )
            )
        );
    }
}
