<?php

namespace KoalaFacade\DiamondConsole\Foundation;

use CuyZ\Valinor\Mapper\MappingError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Tappable;
use KoalaFacade\DiamondConsole\Contracts\DataMapper;
use KoalaFacade\DiamondConsole\Foundation\DataTransferObject\HasResolvable;
use Spatie\Cloneable\Cloneable;

abstract readonly class DataTransferObject
{
    use HasResolvable;
    use Tappable;
    use Cloneable;
    use Conditionable;

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
     * Prevent properties to included on update
     *
     * @return array<empty>
     */
    protected function toExcludedPropertiesOnUpdate(): array
    {
        return [];
    }

    /**
     * Resolve result array-key of toArray method from behaviour
     */
    protected function resolveArrayKey(string $key): string
    {
        return Str::snake(value: $key);
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
     * Die and dump the current Data.
     */
    public function dd(): never
    {
        dd($this);
    }

    /**
     * Abilities to orchestrate the Data
     */
    public function recycle(mixed ...$values): static
    {
        $callback = Arr::first($values);

        if (Collection::make($values)->containsOneItem() && $callback instanceof \Closure) {
            /** @var static $instance */
            $instance = call_user_func($callback, $this);
        } else {
            $instance = $this->with(...$values);
        }

        return $instance;
    }

    /**
     * @param  array<TKey, TValue> | Model  $data
     *
     * Hydrate incoming data to resolve unstructured data
     *
     * @throws MappingError
     *
     * @template TKey of array-key
     * @template TValue
     */
    public static function hydrate(array | Model $data): static
    {
        /** @var DataMapper $dataMapper */
        $dataMapper = resolve(name: DataMapper::class);

        /** @var static $instance */
        $instance = $dataMapper->execute(
            signature: static::class,
            data: match (true) {
                $data instanceof Model => $data->attributesToArray(),
                default => $data
            }
        );

        return $instance;
    }
}
