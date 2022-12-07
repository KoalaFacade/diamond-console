<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Command\ResolveCommandAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithDDD;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class ProviderMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithDDD, InteractsWithConsole;

    protected $signature = 'infrastructure:make:provider {name} {domain} {--force}';

    protected $description = 'Create a new service provider class';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating provider to your project.');

        ResolveCommandAction::resolve()->execute(command: $this);

        $this->info(string: 'Successfully generate provider file.');
    }

    public function getNamespace(): string
    {
        return $this->resolveNamespace(
            structures: $this->resolveInfrastructurePath(),
            suffix: 'Providers',
            prefix: $this->resolveDomainArgument(),
        );
    }

    public function getStubPath(): string
    {
        return $this->resolveStubForPath(name: 'provider');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
        );
    }
}
