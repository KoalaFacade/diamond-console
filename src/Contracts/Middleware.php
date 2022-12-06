<?php

namespace KoalaFacade\DiamondConsole\Contracts;

interface Middleware
{
    public function resolveMiddleware(): void;
}