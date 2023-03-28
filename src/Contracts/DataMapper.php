<?php

namespace KoalaFacade\DiamondConsole\Contracts;

use CuyZ\Valinor\Mapper\MappingError;
use KoalaFacade\DiamondConsole\Actions\DataMapperAction;

interface DataMapper
{
    /**
     * @throws MappingError
     * @param array<array-key, mixed> $data
     * @return mixed
     * @param class-string | string $signature
     */
    public function execute(string $signature, array $data): mixed;
}