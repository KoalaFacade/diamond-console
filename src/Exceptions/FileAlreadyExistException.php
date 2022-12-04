<?php

namespace KoalaFacade\DiamondConsole\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class FileAlreadyExistException extends \Exception
{
    public function __construct(public string $fileName)
    {
        parent::__construct(
            message: $this->fileName .'already exists.',
            code: Response::HTTP_FORBIDDEN,
        );
    }
}
