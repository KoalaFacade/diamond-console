<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\Support\DiamondConsole;

/**
 * @mixin Console
 */
trait InteractsWithConsole
{
    public function getNamespacePath(): string
    {
        return DiamondConsole::resolveNamespacePath(namespace: $this->getNamespace());
    }

    public function getFileName(): string
    {
        return $this->getClassName() . '.php';
    }

    public function getClassName(): string
    {
        return $this->resolveNameArgument();
    }
}
