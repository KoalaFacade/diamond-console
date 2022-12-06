<?php

namespace KoalaFacade\DiamondConsole\Contracts;

use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;

interface Placeholders
{
    public function resolvePlaceholders(): PlaceholderData;
}
