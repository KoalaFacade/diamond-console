<?php

namespace KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasArrayable
{
    protected Collection $collection;

    protected array $excludedPropertiesOnCreation = [];

    protected array $excludedPropertiesOnUpdate = [];

    /**
     * a method that will resolve the inheritance properties
     * naming to snake case that can fit with database column naming
     *
     * @return Collection
     */
    public function toCollection(): Collection
    {
        $excludedPropertyKeys = [
            "\x00*\x00excludedPropertiesOnCreation",
            "\x00*\x00excludedPropertiesOnUpdate",
        ];

        $this->collection = Collection::wrap((array) $this)
            ->except(keys: $excludedPropertyKeys)
            ->mapWithKeys(fn ($value, $key): array => [Str::snake($key) => $value]);

        return $this->collection;
    }

    public function toArray(): array
    {
        return $this
            ->toCollection()
            ->toArray();
    }

    public function resolveForExcludedPropertiesOnCreation(): array
    {
        return $this->excludedPropertiesOnCreation;
    }

    public function resolveForExcludedPropertiesOnUpdate(): array
    {
        return $this->excludedPropertiesOnUpdate;
    }
}
