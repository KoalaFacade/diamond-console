<?php

namespace KoalaFacade\DiamondConsole\Enums;

enum Layer
{
    case application;

    case domain;

    case infrastructure;

    public function resolveNamespace(string $suffix = ''): string
    {
        $key = $this->name;

        return config(key: 'diamond.structures.' . $key) . $suffix;
    }

    public function resolvePath(string $suffix): string
    {
        return base_path() . '/' . config(key: 'diamond.base_directory') . $this->resolveNamespace(suffix: $suffix);
    }
}
