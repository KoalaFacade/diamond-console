<?php

namespace KoalaFacade\DiamondConsole\Commands\Domain;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class EnumMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'domain:make:enum {name} {domain} {--force}';

    protected $description = 'Create a new enum';

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate enum file');
    }

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating enum file to your project');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                domainArgument: $this->resolveDomainArgument(),
                structures: Source::resolveDomainPath(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Enums',
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'domain/enum');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
        );
    }
}
