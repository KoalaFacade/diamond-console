<?php

namespace KoalaFacade\DiamondConsole\Actions;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use KoalaFacade\DiamondConsole\Contracts\DataMapper;
use KoalaFacade\DiamondConsole\Foundation\Action;

readonly class DataMapperAction extends Action implements DataMapper
{
    public function execute(string $signature, array $data): mixed
    {
        return (new MapperBuilder)
            ->mapper()
            ->map(
                signature: $signature,
                source: Source::array(data: $data)->camelCaseKeys()
            );
    }
}