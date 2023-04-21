<?php

namespace KoalaFacade\DiamondConsole\Foundation;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Tappable;
use KoalaFacade\DiamondConsole\Foundation\DataTransferObject\HasResolvable;
use Spatie\Cloneable\Cloneable;

abstract readonly class DataTransferObject
{
    use HasResolvable;
    use Tappable;
    use Cloneable;

    /**
     * Prevent properties to included on create
     *
     * @return array<empty>
     */
    protected function toExcludedPropertiesOnCreate(): array
    {
        return [];
    }

    /**
     * Prevent properties to included on create
     *
     * @return array<empty>
     */
    protected function toExcludedPropertiesOnUpdate(): array
    {
        return [];
    }

    /**
     * The method that will resolve the inheritance properties
     * naming to snake case that can fit with database column naming
     *
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        $excludedPropertyKeys = [
            "\x00*\x00excludedPropertiesOnCreate",
            "\x00*\x00excludedPropertiesOnUpdate",
            "\x00*\x00resolveArrayKeyUsing",
        ];

        return Collection::wrap((array) $this)
            ->except(keys: $excludedPropertyKeys)
            ->mapWithKeys(
                fn ($value, $key): array => [$this->resolveArrayKey($key) => $value]
            )
            ->toArray();
    }

    /**
     * Resolve result array-key of toArray method from behaviour
     */
    protected function resolveArrayKey(string $key): string
    {
        return Str::snake(value: $key);
    }

    /**
     * Die and dump the current Data.
     */
    public function dd(): never
    {
        dd($this);
    }
}
