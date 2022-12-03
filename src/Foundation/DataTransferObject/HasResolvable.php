<?php

namespace KoalaFacade\DiamondConsole\DataTransferObject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

trait HasResolvable
{
    public static function resolve(mixed $data): static
    {
        return throw new \RuntimeException;
    }

    public static function resolveFrom(mixed $abstract): static
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
