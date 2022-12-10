<?php

namespace KoalaFacade\DiamondConsole\Actions\Factory;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;
use KoalaFacade\DiamondConsole\Support\Source;

readonly class FactoryContractMakeAction extends Action implements Console
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
        return Source::resolveStubForPath(name: 'factory-contract');
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            data: new NamespaceData(
                structures: Source::resolveDomainPath(),
                domainArgument: 'Shared',
                nameArgument: $this->resolveNameArgument(),
                endsWith: 'Contracts\\Database\\Factories',
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
        return $this->console->resolveNameArgument();
    }

    public function afterCreate(): void
    {
        $this->console->info(
            string: 'Succeed generate Factory Interface at ' . $this->getFullPath()
        );
    }
}
