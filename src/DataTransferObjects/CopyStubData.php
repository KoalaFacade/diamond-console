<?php

namespace KoalaFacade\DiamondConsole\DataTransferObjects;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

final class CopyStubData extends DataTransferObject
{
    /**
     * @param  string  $stubPath
     * @param  string  $targetPath
     * @param  string  $fileName
     * @param  PlaceholderData  $placeholders
     */
    public function __construct(
        public string $stubPath,
        public string $targetPath,
        public string $fileName,
        public PlaceholderData $placeholders,
    ) {
        //
    }
}
