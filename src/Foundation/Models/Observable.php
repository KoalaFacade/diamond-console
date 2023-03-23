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
    /**
     * @var array<int, string>
     */
    protected static array $observers = [];

    public static function bootObservable(): void
    {
        $namespace = self::class;

        $className = class_basename(class: $namespace);

        $domainOfModel = Str::of(string: $namespace)
            ->after(search: 'Shared')
            ->before(search: 'Models')
            ->toString();

        static::$observers[] = Layer::infrastructure->resolveNamespace(suffix: $domainOfModel . 'Database\\Observe\\' . $className . 'Observe');

        $observers = array_unique(array: static::$observers);

        foreach ($observers as $observerNamespace) {
            if (class_exists($observerNamespace)) {
                (new self)->registerObserver(class: $observerNamespace);
            }
        }
    }
}
