<?php

namespace KoalaFacade\DiamondConsole\Contracts;

interface LifeCycle
{
    public function beforeCreate(): void;

    public function afterCreate(): void;
}
