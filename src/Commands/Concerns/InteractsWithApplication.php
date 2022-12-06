<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use KoalaFacade\DiamondConsole\Contracts\Console;

/**
 * @mixin Console
 */
trait InteractsWithApplication
{
    public function getNamespacePath(): string
    {
        return $this->resolveNamespacePath(namespace: $this->getNamespace());
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
