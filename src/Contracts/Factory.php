<?php

namespace KoalaFacade\DiamondConsole\Contracts;

interface Factory
{
    public function resolveFactory(): \Illuminate\Database\Eloquent\Factories\Factory;
}