<?php

namespace KoalaFacade\DiamondConsole\Support;

use Illuminate\Support\Str;

class Source
{
    public static function resolvePathForStructure(string $key): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.structures.' . $key);

        return $path;
    }

    public static function resolveBasePath(): string
    {
        /** @var string $path */
        $path = config(key: 'diamond.base_directory');

        return $path;
    }

    public static function resolveDomainPath(): string
    {
        return static::resolvePathForStructure(key: 'domain');
    }

    public static function resolveInfrastructurePath(): string
    {
        return static::resolvePathForStructure(key: 'infrastructure');
    }

    public static function resolveNamespace(string $structures, string $prefix, string $suffix): string
    {
        return Str::of(string: '\\:prefix\\:suffix')
            ->start($structures)
            ->replace(search: ':prefix', replace: $prefix)
            ->replace(search: ':suffix', replace: $suffix);
    }

    public static function resolveNamespacePath(string $namespace): string
    {
        return base_path(
            path: static::resolveBasePath() . Str::replace(
                search: '\\',
                replace: '/',
                subject: $namespace
            )
        );
    }

    public static function resolveNameFromPHP(string $name): string
    {
        return static::resolveNameFromFile(name: $name, suffix: 'php');
    }

    public static function resolveNameFromFile(string $name, string $suffix): string
    {
        return Str::replace(search: Str::start($suffix, prefix: '.'), replace: '', subject: $name);
    }

    public static function resolveStubForPath(string $name): string
    {
        return Str::replace(search: ':name', replace: $name, subject: __DIR__ . '/../../stubs/:name.stub');
    }
}
