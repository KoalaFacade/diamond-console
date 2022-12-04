<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

class FilePresentData extends DataTransferObject
{
    public function __construct(
        public string $fileName,
        public string $destinationPath,
    ) {
    }
}
