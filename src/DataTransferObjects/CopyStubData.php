<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

final class CopyStubData extends DataTransferObject
{
    /**
     * @param  string  $stubPath
     * @param  string  $destinationPath
     * @param  string  $fileName
     * @param  PlaceholderData  $placeholders
     */
    public function __construct(
        public string $stubPath,
        public string $destinationPath,
        public string $fileName,
        public PlaceholderData $placeholders,
    ) {
        //
    }
}
