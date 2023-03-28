<?php

namespace KoalaFacade\DiamondConsole\Foundation;

abstract readonly class Action
{
    /**
     * Resolve an action class
     *
     * @param  array<array-key, mixed>  $parameters
     */
    public static function resolve(array $parameters = []): static
    {
        /**
         * @var static $static
         */
        $static = resolve(name: static::class, parameters: $parameters);

        return $static;
    }
}
