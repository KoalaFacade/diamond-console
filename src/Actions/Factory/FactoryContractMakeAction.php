<?php

namespace KoalaFacade\DiamondConsole\Actions\Factory;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Command\ResolveCommandAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithApplication;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithDDD;
use KoalaFacade\DiamondConsole\Commands\FactoryMakeCommand;
use KoalaFacade\DiamondConsole\Contracts\Application;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Foundation\Action;

class FactoryContractMakeAction extends Action implements Application
{
    use InteractsWithDDD, InteractsWithApplication;

    public FactoryMakeCommand $command;

    /**
     * @param  FactoryMakeCommand  $command
     */
    public function setCommand(FactoryMakeCommand $command): void
    {
        $this->command = $command;
    }

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function execute(FactoryMakeCommand $command): static
    {
        $this->setCommand(command: $command);

        ResolveCommandAction::resolve()->execute(command: $this);

        $this->command->info(string: 'Succeed generate Factory Interface at ' . $this->command->getNamespacePath() . '/' . $this->command->getFileName());

        return $this;
    }

    public function getStubPath(): string
    {
        return $this->resolveStubForPath(name: 'factory-contract');
    }

    public function getNamespace(): string
    {
        return $this->resolveNamespace(
            structures: $this->resolveDomainPath(),
            suffix: 'Contracts\\Database\\Factories',
            prefix: 'Shared',
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
        return $this->command->resolveForceOption();
    }

    public function resolveNameArgument(): string
    {
        return $this->command->resolveNameArgument();
    }
}
