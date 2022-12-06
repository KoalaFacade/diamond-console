<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Support\Str;

trait InteractsWithDDD
{
    protected function resolvePathForStructure(string $key): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.structures.' . $key);

        return $path;
    }

    protected function resolveBasePath(): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.base_directory');

        return $path;
    }

    protected function resolveDomainPath(): string
    {
        return $this->resolvePathForStructure(key: 'domain');
    }

    protected function resolveInfrastructurePath(): string
    {
        return $this->resolvePathForStructure(key: 'infrastructure');
    }

    protected function resolveNamespace(string $structures, string $suffix, string $prefix): string
    {
        return Str::of(string: '\\:prefix\\:suffix')
            ->start($structures)
            ->replace(search: ':prefix', replace: $prefix)
            ->replace(search: ':suffix', replace: $suffix);
    }

    protected function resolveNamespacePath(string $namespace): string
    {
        return base_path(
            path: $this->resolveBasePath() . Str::replace(
                search: '\\',
                replace: '/',
                subject: $namespace
            )
        );
    }

    protected function resolveNameFromPhp(string $name): string
    {
        return $this->resolveNameFromFile(name: $name, suffix: 'php');
    }

    protected function resolveNameFromFile(string $name, string $suffix): string
    {
        return Str::replace(search: Str::start($suffix, prefix: '.'), replace: '', subject: $name);
    }

    protected function resolveStubForPath(string $name): string
    {
        return Str::replace(search: ':name', replace: $name, subject: __DIR__. '/../../../stubs/:name.stub');
    }
}
