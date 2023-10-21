<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class PlaceholderData extends DataTransferObject
{
    final public function __construct(
        public ?string $namespace = null,
        public ?string $class = null,
        public ?string $subject = null,
        public ?string $tableName = null,
        public ?string $contractName = null,
        public ?string $contractNamespace = null,
        public ?string $contractAlias = null,
        public ?string $event = null,
        public ?string $eventNamespace = null,
        public ?string $model = null,
        public ?string $modelNamespace = null,
    ) {
    }

    protected function resolveArrayKey(string $key): string
    {
        return Str::camel($key);
    }
}
