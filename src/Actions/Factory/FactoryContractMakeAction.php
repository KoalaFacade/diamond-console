<?php

namespace KoalaFacade\DiamondConsole\Actions\Factory;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Commands\FactoryMakeCommand;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;
use KoalaFacade\DiamondConsole\Support\Source;

class FactoryContractMakeAction extends Action implements Console
{
    use InteractsWithConsole;

    public FactoryMakeCommand $console;

    /**
     * @param  FactoryMakeCommand  $console
     */
    public function setConsole(FactoryMakeCommand $console): void
    {
        $this->console = $console;
    }

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function execute(FactoryMakeCommand $console): static
    {
        $this->setConsole(console: $console);

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
            structures: Source::resolveDomainPath(),
            prefix: 'Shared',
            suffix: 'Contracts\\Database\\Factories',
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
