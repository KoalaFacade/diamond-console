<?php

namespace KoalaFacade\DiamondConsole\Contracts;

interface Console
{
    public function getNamespacePath(): string;

    public function getNamespace(): string;

    public function getFileName(): string;

    public function getStubPath(): string;

    public function getClassName(): string;
}
