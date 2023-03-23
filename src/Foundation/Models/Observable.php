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
    protected static array $additionalObservers = [];

    public static function bootObservable(): void
    {
        $namespace = self::class;

        $className = class_basename(class: $namespace);

        $domainOfModel = Str::of(string: $namespace)
            ->after(search: 'Shared')
            ->before(search: 'Models')
            ->toString();

        $observeNamespace = Layer::infrastructure->resolveNamespace(suffix: $domainOfModel . 'Database\\Observe\\' . $className . 'Observe');

        static::$additionalObservers[] = $observeNamespace;

        foreach (static::$additionalObservers as $observerNamespace) {
            if (class_exists($observerNamespace)) {
                (new self)->registerObserver(class: $observerNamespace);
            }
        }
    }
}
