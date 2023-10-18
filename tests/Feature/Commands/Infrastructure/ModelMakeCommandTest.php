<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Model class')
    ->tap(function () {
        $fileName = '/Database/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:model User User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Model class with separator')
    ->tap(function () {
        $fileName = '/Database/Models/Foo/bar.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:model Foo/bar User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Model class')
    ->tap(function () {
        $fileName = '/Database/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:model User User');
        Artisan::call(command: 'infrastructure:make:model User User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Model class with Migration')
    ->tap(function () {
        $fileName = '/Database/Models/User.php';

        if (File::exists(basePath() . domainPath() . $fileName)) {
            unlink(basePath() . domainPath() . $fileName);
        }

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:model User User -m');

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(File::exists(path: base_path('database/migrations/' . $migrationName)))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: File::get(base_path('database/migrations/' . $migrationName)),
                    needles: ['{{ table_name }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Model class with Migration')
    ->tap(function () {
        $fileName = '/Database/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:model User User -m');
        Artisan::call(command: 'infrastructure:make:model User User -m --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(File::exists(path: base_path('database/migrations/' . $migrationName)))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: File::get(base_path('database/migrations/' . $migrationName)),
                    needles: ['{{ table_name }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate Model with factory')
    ->tap(function () {
        $fileContract = '/Contracts/Database/Factories/User.php';
        $fileConcrete = '/Database/Factories/UserFactory.php';
        $fileModel = '/Database/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileConcrete, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:model User User --factory --force');

        expect(value: fileExists(relativeFileName: $fileContract, domain: 'Shared', prefix: 'User'))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileConcrete, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileModel, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileModel, domain: 'User', prefix: infrastructurePath()),
                    needles: [
                        '{{ class }}',
                        '{{ namespace }}',
                        '{{ factoryContract }}',
                        '{{ factoryContractAliast }}',
                        '{{ factoryContractNamespace }}',
                    ]
                )
            )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileConcrete, domain: 'User', prefix: infrastructurePath()),
                    needles: [
                        '{{ factoryContract }}',
                        '{{ factoryContractNamespace }}',
                        '{{ model }}',
                        '{{ modelNamespace }}',
                    ]
                )
            )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileContract, domain: 'Shared', prefix: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Model, if the Model already exists')
    ->tap(function () {
        $fileName = '/Database/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:model User User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:model User User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
