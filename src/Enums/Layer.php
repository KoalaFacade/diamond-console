<?php

namespace KoalaFacade\DiamondConsole\Enums;

enum Layer
{
    case application;

    case domain;

    case infrastructure;

    public function resolveNamespace(string $prefix = '', string $suffix = ''): string
    {
        $key = $this->name;

        return $prefix . config(key: 'diamond.structures.' . $key) . $suffix;
    }

    public function resolvePath(string $prefix, string $suffix): string
    {
        return base_path() . '/' . config(key: 'diamond.base_directory') . $prefix . '/' . $this->resolveNamespace(suffix: $suffix);
    }
}
