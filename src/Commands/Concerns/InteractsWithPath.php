<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait InteractsWithPath
{
    protected function resolvePathForStructure(string $identifier): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.structures.' . $identifier);

        return $path;
    }

    protected function resolvePathForDomain(): string
    {
        return $this->resolvePathForStructure(identifier: 'domain');
    }

    protected function resolvePathForInfrastructure(): string
    {
        return $this->resolvePathForStructure(identifier: 'infrastructure');
    }

    protected function resolveBasePath(): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.base_directory');

        return $path;
    }

    protected function resolveDestinationByNamespace(Stringable $namespace): string
    {
        return base_path(
            path: $this->resolveBasePath() . $namespace->replace(search: '\\', replace: '/')->toString()
        );
    }

    protected function resolvePathForStub(string $name): string
    {
        return __DIR__ . '/../../../stubs/' . $name . '.stub';
    }

    protected function resolveClassNameByFile(string $name, string $extension = '.php'): string
    {
        return Str::of(string: $name)->replace(search: $extension, replace: '')->toString();
    }
}
