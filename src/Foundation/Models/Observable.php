<?php

namespace KoalaFacade\DiamondConsole\Foundation\Models;

use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Enums\Layer;
use KoalaFacade\DiamondConsole\Models\Model;

/**
 *@mixin Model
 */
trait Observable
{
    public static function bootObservable(): void
    {
        $namespace = static::class;

        $className = class_basename(class: $namespace);

        $domain = Str::of(string: $namespace)
            ->after(search: 'Shared')
            ->before(search: 'Models')
            ->toString();

        $observeClass = Layer::infrastructure->resolveNamespace(suffix: $domain . 'Database\\Observe\\' . $className . 'Observe');

        if (class_exists($observeClass)) {
            (new $namespace)->registerObserver(class: $observeClass);
        }
    }
}
