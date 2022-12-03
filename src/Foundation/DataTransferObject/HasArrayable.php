<?php

namespace KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasArrayable
{
    /**
     * Prevent properties to included on update
     *
     * @var array
     */
    protected array $excludedPropertiesOnCreate = [];

    /**
     * Prevent properties to included on update
     *
     * @var array
     */
    protected array $excludedPropertiesOnUpdate = [];

    /**
     * a method that will resolve the inheritance properties
     * naming to snake case that can fit with database column naming
     *
     * @return array
     */
    public function toArray(): array
    {
        $excludedPropertyKeys = [
            "\x00*\x00excludedPropertiesOnCreation",
            "\x00*\x00excludedPropertiesOnUpdate",
        ];

        return Collection::wrap((array) $this)
            ->except(keys: $excludedPropertyKeys)
            ->mapWithKeys(callback: fn ($value, $key): array => [Str::snake($key) => $value])
            ->toArray();
    }

    /**
     * Lookup the properties those excluded in create
     *
     * @return array
     */
    public function toExcludedPropertiesOnCreate(): array
    {
        return $this->excludedPropertiesOnCreate;
    }

    /**
     * Lookup the properties those excluded in create
     *
     * @return array
     */
    public function toExcludedPropertiesOnUpdate(): array
    {
        return $this->excludedPropertiesOnUpdate;
    }
}
