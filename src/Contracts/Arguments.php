<?php

namespace KoalaFacade\DiamondConsole\Contracts;

interface Arguments
{
    public function resolveNameArgument(): string;

    public function resolveDomainArgument(): string;
}
