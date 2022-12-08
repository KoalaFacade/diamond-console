<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class CopyStubData extends DataTransferObject
{
    final public function __construct(
        public string $stubPath,
        public string $targetPath,
        public string $fileName,
        public PlaceholderData $placeholders,
    ) {
    }
}
