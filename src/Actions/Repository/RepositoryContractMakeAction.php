<?php

namespace KoalaFacade\DiamondConsole\Actions\Repository;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Composer\ResolveComposerAutoLoaderAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;
use KoalaFacade\DiamondConsole\Support\Source;

readonly class RepositoryContractMakeAction extends Action implements Console
{
    use InteractsWithConsole;

    public function __construct(
        protected Console & Command $console
    ) {
    }

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function execute(): static
    {
        $this->handle();

        return $this;
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'infrastructure/repository-contract');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                domainArgument: 'Shared',
                structures: $this->resolveDomainArgument(),
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Contracts\\Repositories',
            )
        );
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName()
        );
    }

    public function resolveForceOption(): bool
    {
        return $this->console->resolveForceOption();
    }

    public function resolveNameArgument(): string
    {
        return Str::replaceLast(search: 'Repository', replace: '', subject: $this->console->resolveNameArgument());
    }

    public function resolveDomainArgument(): string
    {
        return $this->console->resolveDomainArgument();
    }

    public function afterCreate(): void
    {
        $this->console->info(
            string: 'Succeed generate Repository Interface at ' . $this->getFullPath()
        );
    }

    public function beforeCreate(): void
    {
        $filesystem = new Filesystem;

        if (! $filesystem->exists(Source::resolveBasePath() . '/Shared')) {
            ResolveComposerAutoLoaderAction::resolve()->execute(domain: 'Shared');
        }
    }
}