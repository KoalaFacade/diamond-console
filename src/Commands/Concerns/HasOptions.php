<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Console\Command;

/**
 * @mixin Command
 */
trait HasOptions
{
    protected function resolveForceOption(): bool
    {
        return (bool) $this->option(key: 'force');
    }
}
