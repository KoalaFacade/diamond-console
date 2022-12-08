<?php

namespace KoalaFacade\DiamondConsole\Foundation;

readonly abstract class Action
{
    /**
     * Resolve an action class
     *
     * @param  array<array-key, mixed>  $parameters
     * @return static
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
