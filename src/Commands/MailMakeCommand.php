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

class MailMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'infrastructure:make:mail {name} {domain} {--force}';

    protected $description = 'Create a new mail';

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating file to our project');
    }

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate base file');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveInfrastructurePath(),
                domainArgument: $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Mails',
            )
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'mail');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
            subject: $this->resolveNameArgument(),
        );
    }
}
