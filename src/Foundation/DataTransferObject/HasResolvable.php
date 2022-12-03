<?php

namespace KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

trait HasResolvable
{
    /**
     * @template Tkey of array-key
     * @template Tvalue
     *
     * @param  array<Tkey, Tvalue>  $data
     * @return static
     */
    public static function resolve(array $data): static
    {
        return throw new \RuntimeException;
    }

    /**
     * @template Tkey of array-key
     * @template Tvalue
     *
     * @param  FormRequest|Model|array<Tkey, Tvalue>  $abstract
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
            return static::resolve(data: $abstract);
        }

        return throw new \RuntimeException;
    }

    public static function resolveFromFormRequest(FormRequest $request): static
    {
        return throw new \RuntimeException;
    }

    public static function resolveFromModel(Model $model): static
    {
        return throw new \RuntimeException;
    }
}
