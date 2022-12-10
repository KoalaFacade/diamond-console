<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        if (File::exists(basePath() . domainPath() . '/Shared/User/Models/User.php')) {
            unlink(basePath() . domainPath() . '/Shared/User/Models/User.php');
        }

        expect(fileExists($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User');

        expect(fileExists($fileName))->toBeTrue();

        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new model class with separator')
    ->tap(function () {
        $fileName = '/Shared/User/Models/Foo/Bar.php';

        expect(fileExists($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model Foo/Bar User');

        expect(fileExists($fileName))->toBeTrue();

        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(fileExists($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User');
        Artisan::call(command: 'domain:make:model User User --force');

        expect(fileExists($fileName))->toBeTrue();

        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new model class with migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(fileExists($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User -m');

        expect(fileExists($fileName))->toBeTrue();

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';
        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists model class with migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(fileExists($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User -m');
        Artisan::call(command: 'domain:make:model User User -m --force');

        expect(fileExists($fileName))->toBeTrue();

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';
        $modelFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $modelFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate model with factory')
    ->tap(function () {
        $factoryName = 'RoleFactory';
        $domainName = 'Role';
        $modelName = 'Role';

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';
        $modelConcretePath = basePath() . domainPath() . '/Shared/' . $domainName . '/Models/' . $modelName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse();

        Artisan::call(command: 'domain:make:model ' . $modelName . ' ' . $domainName . ' --factory --force');

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value: File::exists(path: $modelConcretePath))->toBeTrue();

        $modelConcretePath = File::get(path: $modelConcretePath);

        expect(value: Str::contains(haystack: $modelConcretePath, needles: ['{{ factory_contract }}', '{{ factory_contract_namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Model, if the Model already exists')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'domain:make:model User User');

        expect(fileExists(relativeFileName: $fileName))->toBeTrue();

        Artisan::call(command: 'domain:make:model User User');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
