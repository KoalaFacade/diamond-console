<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate new DTO',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        if (File::exists(base_path($basePath . $domainPath . '/Post/Enums/PostData.php'))) {
            unlink(base_path($basePath . $domainPath . '/Post/Enums/PostData.php'));
        }

        $this->assertFalse(File::exists(base_path($basePath . $domainPath . '/Post/DataTransferObjects/PostData.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');

        $this->assertTrue(File::exists(base_path($basePath . $domainPath . '/Post/DataTransferObjects/PostData.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands');

it(
    description: 'can force generate exists DTO',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        $this->assertFalse(File::exists(base_path($basePath . $domainPath . '/Post/DataTransferObjects/StoreUserAction.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');
        Artisan::call(command: 'diamond:dto PostData Post');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'PostData.php already exists.'));

        Artisan::call(command: 'diamond:dto PostData Post --force');

        $this->assertTrue(File::exists(base_path($basePath . $domainPath . '/Post/DataTransferObjects/PostData.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands');
