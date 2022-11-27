<?php

use Illuminate\Support\Arr;
use KoalaFacade\DiamondConsole\Actions\Composer\ResolveComposerAutoLoaderAction;

afterEach(closure: function () {
    $composer = fetchComposerContents();

    foreach (supportedStructures() as $namespace) {
        Arr::forget(array: $composer->autoload['psr-4'], keys: $namespace);
    }

    (new Illuminate\Filesystem\Filesystem())->put(
        path: base_path('composer.json'),
        contents: json_encode(
            value: $composer,
            flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        )
    );
});

it(
    description: 'can automatically add autoloader into composer',
    closure: function () {
        $composerResolver = ResolveComposerAutoLoaderAction::resolve()->execute();

        $loadedComposer = fetchComposerContents();
        $supportedStructureNamespaces = supportedStructures();

        expect($composerResolver)->toBeTrue()
            ->and(array_keys($loadedComposer->autoload['psr-4']))->toContain(...$supportedStructureNamespaces);
    }
)->group('composer');

function fetchComposerContents(): object
{
    $path = base_path('composer.json');

    return (object) json_decode(
        json: (new Illuminate\Filesystem\Filesystem())->get($path),
        associative: true
    );
}

function supportedStructures(): array
{
    return ['Infrastructure\\', 'Domain\\'];
}
