<?php

namespace KoalaFacade\DiamondConsole\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Factory
{
    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory<Model>
     */
    public function resolveFactory(): \Illuminate\Database\Eloquent\Factories\Factory;
}
