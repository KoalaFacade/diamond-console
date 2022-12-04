<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

class PlaceholderData extends DataTransferObject
{
    use DataTransferObject\HasArrayable;

    /**
     * @param  string | null  $namespace
     * @param  string | null  $class
     * @param  string | null  $subject
     * @param  string | null  $tableName
     * @param  string | null  $factoryContract
     */
    public function __construct(
        public null | string $namespace = null,
        public null | string $class = null,
        public null | string $subject = null,
        public null | string $tableName = null,
        public null | string $factoryContract = null,
        public null | string $factoryContractNamespace = null,
    ) {
    }
}
