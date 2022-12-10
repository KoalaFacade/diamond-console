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

class SeederMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'infrastructure:make:seeder {name} {domain} {--force}';

    protected $description = 'Create seeder file';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating seeder to your project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Success generate seeder file at ' . $this->getFullPath());
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'seeder');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->resolveNameArgument()
        );
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveInfrastructurePath(),
                domainArgument: $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Database\\Seeders',
            )
        );
    }
}
