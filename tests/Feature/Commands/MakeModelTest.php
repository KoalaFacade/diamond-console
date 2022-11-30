<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate new model class',
    closure: function () {
        if (File::exists(base_path('src/Infrastructure/User/Model/User.php'))) {
            unlink(base_path('src/Infrastructure/User/Model/User.php'));
        }

        $this->assertFalse(File::exists(base_path('src/Infrastructure/User/Model/User.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');

        $this->assertTrue(File::exists(base_path('src/Infrastructure/User/Model/User.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');

it(
    description: 'can force generate exists model class',
    closure: function () {
        $this->assertFalse(File::exists(base_path('src/Infrastructure/User/Model/User.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');
        Artisan::call(command: 'diamond:model User User');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'User.php already exists.'));

        Artisan::call(command: 'diamond:model User User --force');

        $this->assertTrue(File::exists(base_path('src/Infrastructure/User/Model/User.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');
