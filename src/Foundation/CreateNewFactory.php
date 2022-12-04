<?php

namespace KoalaFacade\DiamondConsole\Foundation;

use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Illuminate\Database\Eloquent\Model;
use KoalaFacade\DiamondConsole\Contracts\Factory;

/**
 * @extends EloquentFactory<Model>
 */
abstract class CreateNewFactory extends EloquentFactory implements Factory
{
    /**
     * Resolver method for dependency injection
     *
     * @return EloquentFactory<Model>
     */
    public function resolveFactory(): EloquentFactory
    {
        return static::new();
    }
}
