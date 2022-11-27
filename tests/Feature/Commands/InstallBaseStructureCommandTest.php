<?php

use Illuminate\Support\Facades\Artisan;

afterEach(closure: function () {
    (new Illuminate\Filesystem\Filesystem())
        ->deleteDirectory(directory: base_path(path: config(key: 'diamond.base_directory')));
});

it(
    description: 'can generate base structure',
    closure: function () {
        $baseDirectoryPath = base_path(path: config(key: 'diamond.base_directory'));
        $baseStructures = config(key: 'diamond.structures');

        $this->assertFalse(condition: file_exists($baseDirectoryPath));

        foreach ($baseStructures as $structure) {
            $this->assertFalse(condition: file_exists($baseDirectoryPath . $structure));
        }

        Artisan::call(command: 'diamond:install');

        $this->assertTrue(condition: file_exists($baseDirectoryPath));

        foreach ($baseStructures as $structure) {
            $this->assertTrue(condition: file_exists($baseDirectoryPath . $structure));
        }
    }
)->group('commands');
