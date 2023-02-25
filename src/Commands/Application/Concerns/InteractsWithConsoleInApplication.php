<?php

namespace KoalaFacade\DiamondConsole\Commands\Application\Concerns;

use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
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

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: Str::ucfirst(string: $this->getNamespace()),
            class: $this->getClassName(),
        );
    }
}
