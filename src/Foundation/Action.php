<?php

namespace KoalaFacade\DiamondConsole\Foundation;

abstract class Action
{
    /**
     * Resolve an action class
     *
     * @return static
     */
    public static function resolve(): static
    {
        /**
         * @var static $static
         */
        $static = resolve(name: static::class);

        return $static;
    }
}
