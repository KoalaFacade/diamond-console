<?php

namespace KoalaFacade\DiamondConsole\Contracts;

use CuyZ\Valinor\Mapper\MappingError;
use KoalaFacade\DiamondConsole\Actions\DataMapperAction;

interface DataMapper
{
    /**
     * @throws MappingError
     * @param array $data
     * @return $this|DataMapperAction
     * @param class-string | string $signature
     */
    public function execute(string $signature, array $data): mixed;
}