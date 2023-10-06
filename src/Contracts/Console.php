<?php

namespace KoalaFacade\DiamondConsole\Contracts;

interface Console extends Arguments, LifeCycle, Options, Placeholders
{
    public function getFullPath(): string;

    public function getNamespacePath(): string;

    public function getNamespace(): string;

    public function getFileName(): string;

    public function getStubPath(): string;

    public function getClassName(): string;
}
