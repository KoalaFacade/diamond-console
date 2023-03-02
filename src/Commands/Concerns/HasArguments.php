<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * @mixin Command
 */
trait HasArguments
{
    protected function resolveDomainArgument(): string
    {
        /** @var string $argument */
        $argument = $this->argument(key: 'domain');

        return Str::ucfirst(string: $argument);
    }

    public function resolveNameArgument(): string
    {
        /** @var string $argument */
        $argument = $this->argument(key: 'name');

        return Str::ucfirst(string: $argument);
    }
}
