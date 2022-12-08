<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class FilePresentData extends DataTransferObject
{
    final public function __construct(
        public string $fileName,
        public string $namespacePath,
    ) {
    }
}
