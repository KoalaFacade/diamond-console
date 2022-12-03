<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Console\Command;

/**
 * @mixin Command
 */
trait HasBaseArguments
{
    protected function resolveArgumentForDomain(): string
    {
        /** @var string $argument */
        $argument = $this->argument(key: 'domain');

        return $argument;
    }

    protected function resolveArgumentForName(): string
    {
        /** @var string $argument */
        $argument = $this->argument(key: 'name');

        return $argument;
    }
}
