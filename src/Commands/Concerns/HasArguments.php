<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Console\Command;

/**
 * @mixin Command
 */
trait HasArguments
{
    protected function resolveDomainArgument(): string
    {
        /** @var string $argument */
        $argument = $this->argument(key: 'domain');

        return $argument;
    }

    public function resolveNameArgument(): string
    {
        /** @var string $argument */
        $argument = $this->argument(key: 'name');

        return $argument;
    }
}
