<?php

namespace KoalaFacade\DiamondConsole\Support;

use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\DataTransferObjects\NamespaceData;
use KoalaFacade\DiamondConsole\Enums\Layer;

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
        return static::resolvePathForStructure(key: Layer::domain->name);
    }

    public static function resolveInfrastructurePath(): string
    {
        return static::resolvePathForStructure(key: Layer::infrastructure->name);
    }

    public static function resolveApplicationPath(): string
    {
        return static::resolvePathForStructure(key: Layer::application->name);
    }

    public static function resolveNamespace(NamespaceData $data): string
    {
        /** @var string $result */
        $result = Str::replace(
            search: '/',
            replace: '\\',
            subject: static::resolveNamespaceDir(
                data: $data,
                namespace: Str::of(string: '/')
                    ->start(prefix: $data->domainArgument)
                    ->append(values: $data->structures)
                    ->finish(cap: $data->endsWith ? '/' . $data->endsWith : '')
            )
        );

        return $result;
    }

    public static function resolveNamespaceDir(NamespaceData $data, string $namespace): string
    {
        return Str::contains(haystack: $data->nameArgument, needles: '/') ?
            Str::of(string: '/')
                ->start(prefix: $namespace)
                ->finish(cap: dirname($data->nameArgument))
            : $namespace;
    }

    public static function resolveNamespacePath(string $namespace): string
    {
        return base_path(
            path: static::resolveBasePath() . static::transformNamespaceToPath(namespace: $namespace)
        );
    }

    public static function transformNamespaceToPath(string $namespace): string
    {
        /** @var string $result */
        $result = Str::replace(
            search: '\\',
            replace: '/',
            subject: $namespace
        );

        return $result;
    }

    public static function resolveNameFromPHP(string $name): string
    {
        return static::resolveNameFromFile(name: $name, suffix: 'php');
    }

    public static function resolveNameFromFile(string $name, string $suffix): string
    {
        /** @var string $result */
        $result = Str::replace(search: Str::start($suffix, prefix: '.'), replace: '', subject: $name);

        return $result;
    }

    public static function resolveStubForPath(string $name): string
    {
        /** @var string $result */
        $result = Str::replace(search: ':name', replace: $name, subject: __DIR__ . '/../../stubs/:name.stub');

        return $result;
    }
}
