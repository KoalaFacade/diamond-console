<?php

namespace KoalaFacade\DiamondConsole\Foundation;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\DataTransferObject\HasResolvable;

abstract class DataTransferObject
{
    use HasResolvable, EvaluatesClosures;

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

    protected Closure | null $resolveArrayKeyUsing = null;

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
                function ($value, $key): array {
                    $evaluate = $this->evaluate(
                        value: $this->resolveArrayKeyUsing,
                        parameters: ['key' => $key]
                    );

                    return [$evaluate ?? Str::snake($key) => $value];
                }
            )
            ->toArray();
    }

    /**
     * Resolve result array-key of toArray method from behaviour
     *
     * @param  Closure | null  $callback
     * @return static
     */
    public function resolveArrayKeyUsing(Closure | null $callback): static
    {
        $this->resolveArrayKeyUsing = $callback;

        return $this;
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
