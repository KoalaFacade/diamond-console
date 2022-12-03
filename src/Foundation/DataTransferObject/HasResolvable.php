<?php

namespace KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

trait HasResolvable
{
    /**
     * Resolve unstructured data from array
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  array<TKey, TValue>  $data
     * @return static
     */
    public static function resolveFromArray(array $data): static
    {
        return throw new \RuntimeException();
    }

    /**
     * Resolve unstructured data from polymorphism types
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  FormRequest | Model | array<TKey, TValue>  $abstract
     * @return static
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
     * Resolve unstructured data from FormRequest
     *
     * @param FormRequest $request
     * @return static
     */
    public static function resolveFromFormRequest(FormRequest $request): static
    {
        return throw new \RuntimeException;
    }

    /**
     * Resolve unstructured data from Model
     *
     * @param Model $model
     * @return static
     */
    public static function resolveFromModel(Model $model): static
    {
        return throw new \RuntimeException;
    }
}
