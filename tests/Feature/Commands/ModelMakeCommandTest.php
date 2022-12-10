<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Model class with separator')
    ->tap(function () {
        $fileName = '/Shared/User/Models/Foo/bar.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model Foo/bar User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User');
        Artisan::call(command: 'domain:make:model User User --force');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Model class with Migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        if (File::exists(basePath() . domainPath() . $fileName)) {
            unlink(basePath() . domainPath() . $fileName);
        }

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User -m');

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(File::exists(path: base_path('database/migrations/' . $migrationName)))->toBeTrue();

        expect(
            value: Str::contains(
                haystack: fileGet(relativeFileName: $fileName),
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
        $fileName = '/Shared/User/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:model User User -m');
        Artisan::call(command: 'domain:make:model User User -m --force');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        $tableName = Str::snake('CreateUsersTable');
        $migrationName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(File::exists(path: base_path('database/migrations/' . $migrationName)))->toBeTrue();

        expect(
            value: Str::contains(
                haystack: fileGet(relativeFileName: $fileName),
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
        $fileContract = '/Shared/Contracts/Database/Factories/UserFactory.php';
        $fileConcrete = '/User/Database/Factories/UserFactory.php';
        $fileModel = '/Shared/User/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileContract))->toBeFalse();

        Artisan::call(command: 'domain:make:model User User --factory --force');

        expect(value: fileExists(relativeFileName: $fileContract))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileModel))->toBeTrue();

        expect(
            value: Str::contains(
                haystack: fileGet(relativeFileName: $fileModel),
                needles: ['{{ class }}', '{{ namespace }}', '{{ factoryContract }}', '{{ factoryContractNamespace }}']
            )
        )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileConcrete, prefix: infrastructurePath()),
                    needles: ['{{ factoryContract }}', '{{ factoryContractNamespace }}']
                )
            )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileContract),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Model, if the Model already exists')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'domain:make:model User User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        Artisan::call(command: 'domain:make:model User User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
