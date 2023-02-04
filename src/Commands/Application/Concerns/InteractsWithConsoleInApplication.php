<?php

namespace KoalaFacade\DiamondConsole\Commands\Application\Concerns;

use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Support\Source;

trait InteractsWithConsoleInApplication
{
    use InteractsWithConsole;

    public function getNamespacePath(): string
    {
        return base_path(
            path: Source::transformNamespaceToPath(namespace: $this->getNamespace())
        );
    }
}
