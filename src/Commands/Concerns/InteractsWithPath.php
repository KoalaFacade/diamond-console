<?php

namespace KoalaFacade\DiamondConsole\Commands\Concerns;

use Illuminate\Support\Str;

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

    protected function resolvePathInfrastructure(): string
    {
        return $this->resolvePathForStructure(identifier: 'infrastructure');
    }

    protected function resolveBasePath(): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.base_directory');

        return $path;
    }

    protected function resolveNamespaceTarget(string $namespace): string
    {
        return base_path(
            path: $this->resolveBasePath() . Str::replace(
                search: '\\',
                replace: '/',
                subject: $namespace
            )
        );
    }

    protected function resolveNamespace(string $identifier, string $domain, string $layer = 'domain'): string
    {
        $layerPath = match ($layer) {
            'infrastructure' => $this->resolvePathInfrastructure(),
            default => $this->resolvePathForDomain()
        };

        return Str::of(string: '')
            ->append(
                $layerPath . '\\',
                $domain . '\\',
                $identifier,
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
