<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use KoalaFacade\DiamondConsole\Enums\Layer;

beforeEach(closure: function () {
    (new Illuminate\Filesystem\Filesystem())
        ->deleteDirectory(directory: base_path(path: config(key: 'diamond.base_directory')));
});

afterEach(closure: function () {
    (new Illuminate\Filesystem\Filesystem())
        ->deleteDirectory(directory: base_path(path: config(key: 'diamond.base_directory')));
});

it(
    description: 'can generate base structure',
    closure: function () {
        $baseDirectoryPath = base_path(path: config(key: 'diamond.base_directory'));
        $baseStructures = Arr::except(array: config(key: 'diamond.structures'), keys: Layer::application->name);

        $this->assertFalse(condition: file_exists($baseDirectoryPath));

        foreach ($baseStructures as $structure) {
            $this->assertFalse(condition: file_exists($baseDirectoryPath . $structure));
        }

        Artisan::call(command: 'diamond:install');

        $this->assertTrue(condition: file_exists($baseDirectoryPath));

        $this->assertTrue(condition: file_exists($baseDirectoryPath . '/' . config(key: 'diamond.structures.infrastructure') . '/Laravel/Providers'));

        $this->assertFalse(condition: file_exists(app_path('Providers')));

        foreach ($baseStructures as $structure) {
            $this->assertTrue(condition: file_exists($baseDirectoryPath . $structure));
        }
    }
)->group('commands');

it(
    description: 'can generate base structure with skip refactor',
    closure: function () {
        $baseDirectoryPath = base_path(path: config(key: 'diamond.base_directory'));
        $baseStructures = Arr::except(array: config(key: 'diamond.structures'), keys: Layer::application->name);

        $this->assertFalse(condition: file_exists($baseDirectoryPath));

        foreach ($baseStructures as $structure) {
            $this->assertFalse(condition: file_exists($baseDirectoryPath . $structure));
        }

        Artisan::call(command: 'diamond:install --skip-refactor');

        $this->assertTrue(condition: file_exists($baseDirectoryPath));

        foreach ($baseStructures as $structure) {
            $this->assertTrue(condition: file_exists($baseDirectoryPath . $structure));
        }
    }
)->group('commands');
