<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class PlaceholderData extends DataTransferObject
{
    final public function __construct(
        public null | string $namespace = null,
        public null | string $class = null,
        public null | string $subject = null,
        public null | string $tableName = null,
        public null | string $factoryContract = null,
        public null | string $factoryContractNamespace = null,
        public null | string $modelName = null,
        public null | string $modelNamespace = null,
    ) {
    }

    protected function resolveArrayKey(string $key): string
    {
        return Str::camel($key);
    }
}
