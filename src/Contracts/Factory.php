<?php

namespace KoalaFacade\DiamondConsole\Contracts;

use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Illuminate\Database\Eloquent\Model;

interface Factory
{
    /**
     * @return EloquentFactory<Model>
     */
    public function resolveFactory(): EloquentFactory;
}
