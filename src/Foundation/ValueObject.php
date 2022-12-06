<?php

namespace KoalaFacade\DiamondConsole\Foundation;

abstract class ValueObject
{
    abstract public static function make(mixed $data): static;
}
