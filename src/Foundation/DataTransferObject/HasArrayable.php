<?php

namespace KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasArrayable
{
    /**
     * Prevent properties to included on update
     *
     * @var array<string>
     */
    protected array $excludedPropertiesOnCreate = [];

    /**
     * Prevent properties to included on update
     *
     * @var array<string>
     */
    protected array $excludedPropertiesOnUpdate = [];

    /**
     * a method that will resolve the inheritance properties
     * naming to snake case that can fit with database column naming
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $excludedPropertyKeys = [
            "\x00*\x00excludedPropertiesOnCreate",
            "\x00*\x00excludedPropertiesOnUpdate",
        ];

        return Collection::wrap((array) $this)
            ->except(keys: $excludedPropertyKeys)
            ->mapWithKeys(
                callback: fn ($value, $key): array => [Str::snake($key) => $value]
            )
            ->toArray();
    }

    /**
     * Lookup the properties those excluded in create
     *
     * @return array<string>
     */
    public function toExcludedPropertiesOnCreate(): array
    {
        return $this->excludedPropertiesOnCreate;
    }

    /**
     * Lookup the properties those excluded in create
     *
     * @return array<string>
     */
    public function toExcludedPropertiesOnUpdate(): array
    {
        return $this->excludedPropertiesOnUpdate;
    }
}
