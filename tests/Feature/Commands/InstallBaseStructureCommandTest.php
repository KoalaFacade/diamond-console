<?php

use Illuminate\Support\Facades\Artisan;

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
        $domain = 'MyApp';
        $baseDirectoryPath = base_path(path: config(key: 'diamond.base_directory'));
        $baseStructures = config(key: 'diamond.structures');

        $this->assertFalse(condition: file_exists($baseDirectoryPath));

        foreach ($baseStructures as $structure) {
            $this->assertFalse(condition: file_exists($baseDirectoryPath . $structure));
        }

        Artisan::call(command: 'domain:install ' . $domain);

        $this->assertTrue(condition: file_exists($baseDirectoryPath));

        // $this->assertTrue(condition: file_exists($baseDirectoryPath . $domain . '/' . config('diamond.structures.infrastructure') . '/Laravel/Providers'));
        //
        // $this->assertFalse(condition: file_exists(app_path('Providers')));

        foreach ($baseStructures as $structure) {
            $this->assertTrue(condition: file_exists($baseDirectoryPath . $domain . '/' . $structure));
        }
    }
)->group('commands');

// it(
//     description: 'can generate base structure with skip refactor',
//     closure: function () {
//         $domain = 'MyApp';
//         $baseDirectoryPath = base_path(path: config(key: 'diamond.base_directory'));
//         $baseStructures = config(key: 'diamond.structures');
//
//         $this->assertFalse(condition: file_exists($baseDirectoryPath));
//
//         foreach ($baseStructures as $structure) {
//             $this->assertFalse(condition: file_exists($baseDirectoryPath . $structure));
//         }
//
//         Artisan::call(command: 'domain:install ' . $domain . ' --skip-refactor');
//
//         $this->assertTrue(condition: file_exists($baseDirectoryPath));
//
//         foreach ($baseStructures as $structure) {
//             $this->assertTrue(condition: file_exists($baseDirectoryPath . $domain . '/' . $structure));
//         }
//     }
// )->group('commands');
