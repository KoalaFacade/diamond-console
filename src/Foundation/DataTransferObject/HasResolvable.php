<?php

namespace KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\MapperBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasResolvable
{
    /**
     * Resolve unstructured data from polymorphism types
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  FormRequest | Model | array<TKey, TValue>  $abstract
     *
     * @throws MappingError
     */
    public static function resolveFrom(FormRequest | Model | array $abstract): static
    {
        if ($abstract instanceof FormRequest) {
            return static::resolveFromFormRequest(request: $abstract);
        }

        if ($abstract instanceof Model) {
            return static::resolveFromModel(model: $abstract);
        }

        if (Arr::accessible($abstract)) {
            return static::resolveFromArray(data: $abstract);
        }

        return throw new \RuntimeException;
    }

    /**
     * Resolve unstructured data from array
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  array<TKey, TValue>  $data
     *
     * @throws MappingError
     */
    public static function resolve(array $data): static
    {
        /** @var static $instance */
        $instance = (new MapperBuilder())
            ->mapper()
            ->map(signature: static::class, source: static::resolveTheArrayKeyForm(data: $data));

        return $instance;
    }

    /**
     * Resolve unstructured data from array
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  array<TKey, TValue>  $data
     *
     * @throws MappingError
     *
     * @deprecated can use resolve()
     */
    public static function resolveFromArray(array $data): static
    {
        return static::resolve($data);
    }

    /**
     * Resolve unstructured data from FormRequest
     */
    public static function resolveFromFormRequest(FormRequest $request): static
    {
        return throw new \RuntimeException;
    }

    /**
     * Resolve unstructured data from Model
     */
    public static function resolveFromModel(Model $model): static
    {
        return throw new \RuntimeException;
    }

    /**
     * Resolve all array key form according the config
     *
     * @template TArrayKey of array-key
     * @template TArrayValue
     *
     * @param  array<TArrayKey, TArrayValue>  $data
     * @return array<TArrayKey, mixed>
     */
    protected static function resolveTheArrayKeyForm(array $data): array
    {
        $array = [];

        foreach ($data as $key => $value) {
            $key = static::resolveArrayKeyOfInput(key: $key);

            if (Arr::accessible(value: $value)) {
                /** @var array<TArrayKey, TArrayValue> $valueContainsArray */
                $valueContainsArray = $value;

                $array[$key] = static::resolveTheArrayKeyForm(data: $valueContainsArray);

                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * Resolve the input of array key to constructor naming
     */
    protected static function resolveArrayKeyOfInput(string $key): string
    {
        return Str::camel(value: $key);
    }
}
