<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(description: 'can generate new model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        if (File::exists(basePath() . domainPath() . '/Shared/User/Models/User.php')) {
            unlink(basePath() . domainPath() . '/Shared/User/Models/User.php');
        }

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');

        expect(filePresent($fileName))->toBeTrue();

        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();

        unlink(basePath() . domainPath() . '/Shared/User/Models/User.php');
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');
        Artisan::call(command: 'diamond:model User User --force');

        expect(filePresent($fileName))->toBeTrue();

        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();

        unlink(basePath() . domainPath() . '/Shared/User/Models/User.php');
    })
    ->group(groups: 'commands');

it(description: 'can generate new model class with migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        if (File::exists(basePath() . domainPath() . '/Shared/User/Models/User.php')) {
            unlink(basePath() . domainPath() . '/Shared/User/Models/User.php');
        }

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');

        expect(filePresent($fileName))->toBeTrue();

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';
        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();

        unlink(base_path("database/migrations/$migrationName"));
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists model class with migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');
        Artisan::call(command: 'diamond:model User User -m --force');

        expect(filePresent($fileName))->toBeTrue();

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';
        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();

        unlink(base_path("database/migrations/$migrationName"));
    })
    ->group(groups: 'commands');

it(description: 'can generate model with factory')
    ->tap(function () {
        $factoryName = 'RoleFactory';
        $domainName = 'Role';
        $modelName = 'Role';

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . 'Contract.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';
        $modelConcretePath = basePath() . domainPath() . '/Shared/' . $domainName . '/Models/' . $modelName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse();

        Artisan::call(command: 'diamond:model ' . $modelName . ' ' . $domainName . ' --factory --force');

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value: File::exists(path: $modelConcretePath))->toBeTrue();

        $modelConcretePath = File::get(path: $modelConcretePath);

        expect(value: Str::contains(haystack: $modelConcretePath, needles: ['{{ factory_contract }}', '{{ factory_contract_namespace }}']))->toBeFalse();

        File::delete(paths: [$factoryContractPath, $factoryConcretePath, $modelConcretePath]);
    })
    ->group(groups: 'commands');
