<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate new model class',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        if (File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php"))) {
            unlink(base_path("$basePath/$domainPath/Shared/User/Models/User.php"));
        }

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands');

it(
    description: 'can force generate exists model class',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');
        Artisan::call(command: 'diamond:model User User');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'User.php already exists.'));

        Artisan::call(command: 'diamond:model User User --force');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands');

it(
    description: 'can generate new model class with migration',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        if (File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php"))) {
            unlink(base_path("$basePath/$domainPath/Shared/User/Models/User.php"));
        }

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
        $tableName = Str::snake(Str::pluralStudly('User'));
        $fileName = now()->format('Y_m_d_his') . '_create_' . $tableName . '_table.php';

        unlink(base_path("database/migrations/$fileName"));
    }
)->group('commands');

it(
    description: 'can force generate exists model class with migration',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');
        Artisan::call(command: 'diamond:model User User -m');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'User.php already exists.'));

        Artisan::call(command: 'diamond:model User User -m --force');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/Shared/User/Models/User.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
        $tableName = Str::snake(Str::pluralStudly('User'));
        $fileName = now()->format('Y_m_d_his') . '_create_' . $tableName . '_table.php';

        unlink(base_path("database/migrations/$fileName"));
    }
)->group('commands');

it(
    description: 'can generate model with factory',
    closure: function () {
        $factoryName = 'RoleFactory';
        $domainName = 'Role';
        $modelName = 'Role';

        $factoryInterfacePath = basePath() . domainPath() . '/Shared/' . $domainName . '/Models' . '/Contracts/' . $factoryName . 'Contract' . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';
        $modelConcretePath = basePath() . domainPath() . '/Shared/' . $domainName . '/Models/' . $modelName . '.php';

        expect(value: File::exists(path: $factoryInterfacePath))->toBeFalse();

        Artisan::call(command: 'diamond:model ' . $modelName . ' ' . $domainName . ' --factory --force');

        expect(value: File::exists(path: $factoryInterfacePath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value: File::exists(path: $modelConcretePath))->toBeTrue();

        $modelConcretePath = File::get(path: $modelConcretePath);

        expect(value: Str::contains(haystack: $modelConcretePath, needles: '{{ model_factory_interface }}'))->toBeFalse();

        File::delete(paths: [$factoryInterfacePath, $factoryConcretePath, $modelConcretePath]);
    }
)->group('commands');
