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

    protected function resolveTableName(): ?string
    {
        /** @var string|null $name */
        $name = $this->option(key: 'create') ?: $this->option(key: 'table');

        return $name;
    }

    public function resolveEventOption(): ?string
    {
        /** @var string|null $name */
        $name = $this->option(key: 'event');

        return $name;
    }

    public function resolveModelOption(): ?string
    {
        /** @var string|null $name */
        $name = $this->option(key: 'model');

        return $name;
    }

    public function resolveRenderOption(): ?string
    {
        /** @var string|null $name */
        $name = $this->option(key: 'render');

        return $name;
    }
}
