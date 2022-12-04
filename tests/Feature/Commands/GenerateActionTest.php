<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate new action class',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        if (File::exists(base_path("$basePath/$domainPath/User/Actions/StoreUserAction.php"))) {
            unlink(base_path("$basePath/$domainPath/User/Actions/StoreUserAction.php"));
        }

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/User/Actions/StoreUserAction.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:action StoreUserAction User');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/User/Actions/StoreUserAction.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands');

it(
    description: 'can force generate exists action class',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/User/Actions/StoreUserAction.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:action StoreUserAction User');
        Artisan::call(command: 'diamond:action StoreUserAction User');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'StoreUserAction.php already exists.'));

        Artisan::call(command: 'diamond:action StoreUserAction User --force');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/User/Actions/StoreUserAction.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands');
