<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate new model class',
    closure: function () {
        if (File::exists(base_path('src/Domain/Shared/User/Models/User.php'))) {
            unlink(base_path('src/Domain/Shared/User/Models/User.php'));
        }

        $this->assertFalse(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');

        $this->assertTrue(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');

it(
    description: 'can force generate exists model class',
    closure: function () {
        $this->assertFalse(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');
        Artisan::call(command: 'diamond:model User User');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'User.php already exists.'));

        Artisan::call(command: 'diamond:model User User --force');

        $this->assertTrue(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');

it(
    description: 'can generate new model class with migration',
    closure: function () {
        if (File::exists(base_path('src/Domain/Shared/User/Models/User.php'))) {
            unlink(base_path('src/Domain/Shared/User/Models/User.php'));
        }

        $this->assertFalse(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');

        $this->assertTrue(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path('src'));
        $tableName = Str::snake(Str::pluralStudly('User'));
        $fileName = now()->format('Y_m_d_his') . '_create_' . $tableName . '_table.php';

        unlink(base_path("database/migrations/$fileName"));
    }
)->group('commands');

it(
    description: 'can force generate exists model class with migration',
    closure: function () {
        $this->assertFalse(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');
        Artisan::call(command: 'diamond:model User User -m');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'User.php already exists.'));

        Artisan::call(command: 'diamond:model User User -m --force');

        $this->assertTrue(File::exists(base_path('src/Domain/Shared/User/Models/User.php')));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path('src'));
        $tableName = Str::snake(Str::pluralStudly('User'));
        $fileName = now()->format('Y_m_d_his') . '_create_' . $tableName . '_table.php';

        unlink(base_path("database/migrations/$fileName"));
    }
)->group('commands');
