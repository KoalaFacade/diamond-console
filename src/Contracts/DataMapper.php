<?php

namespace KoalaFacade\DiamondConsole\Contracts;

use CuyZ\Valinor\Mapper\MappingError;

interface DataMapper
{
    /**
     * @param  array<array-key, mixed>  $data
     * @param  class-string | string  $signature
     *
     * @throws MappingError
     */
    public function execute(string $signature, array $data): mixed;
}
