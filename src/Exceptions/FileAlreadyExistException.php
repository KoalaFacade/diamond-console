<?php

namespace KoalaFacade\DiamondConsole\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class FileAlreadyExistException extends \Exception
{
    public function __construct(public string $fileName)
    {
        parent::__construct(
            message: (string) __(':fileName already exists.', [
                'fileName' => $this->fileName,
            ]),
            code: Response::HTTP_FORBIDDEN,
        );
    }
}
