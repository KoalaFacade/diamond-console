<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class NamespaceData extends DataTransferObject
{
    public function __construct(
        public string $domainArgument,
        public string $nameArgument,
        public string $endsWith
    ) {
    }
}
