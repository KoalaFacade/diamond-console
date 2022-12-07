<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use KoalaFacade\DiamondConsole\Actions\Command\ResolveCommandAction;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Support\Source;

/**
 * @mixin Console
 */
trait InteractsWithConsole
{
    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->beforeCreate();

        ResolveCommandAction::resolve()->execute(console: $this);

        $this->afterCreate();
    }

    public function getNamespacePath(): string
    {
        return Source::resolveNamespacePath(namespace: $this->getNamespace());
    }

    public function getFileName(): string
    {
        return $this->getClassName() . '.php';
    }

    public function getClassName(): string
    {
        return $this->resolveNameArgument();
    }

    public function getFullPath(): string
    {
        return $this->getNamespacePath() . '/' . $this->getFileName();
    }

    public function afterCreate(): void
    {
        //
    }

    public function beforeCreate(): void
    {
        //
    }
}
