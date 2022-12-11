<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Console\Command;

/**
 * @mixin Command
 */
trait HasOptions
{
    public function resolveForceOption(): bool
    {
        return (bool) $this->option(key: 'force');
    }

    protected function resolveFactoryOption(): bool
    {
        return (bool) $this->option(key: 'factory');
    }

    /**
     * @return string|null
     */
    protected function resolveTableName(): string | null
    {
        /** @var string|null $name */
        $name = $this->option(key: 'create') ?: $this->option(key: 'table');

        return $name;
    }

    public function resolveEventOption(): string | null
    {
        /** @var string|null $name */
        $name = $this->option(key: 'event');

        return $name;
    }
}
