<?php

namespace KoalaFacade\DiamondConsole\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;

trait HasFactoryResolver
{
    /**
     * Resolver method for dependency injection
     *
     * @return Factory
     */
    public function resolveFactory(): Factory
    {
        /** @var Factory $factory */
        $factory = new self;

        return $factory;
    }
}
