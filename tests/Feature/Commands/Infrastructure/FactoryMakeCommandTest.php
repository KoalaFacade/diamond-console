<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate Factory Concrete and Interface')
    ->tap(function () {
        $fileContract = '/Shared/Contracts/Database/Factories/UserFactory.php';
        $fileConcrete = '/User/Database/Factories/UserFactory.php';

        expect(value: fileExists(relativeFileName: $fileContract))->toBeFalse()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:factory UserFactory User');

        expect(value: fileExists(relativeFileName: $fileContract))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeTrue()
            ->and(value: Str::contains(
                haystack: Artisan::output(),
                needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
            ))->toBeTrue();

        expect(value: Str::contains(
            haystack: fileGet(relativeFileName: $fileConcrete, prefix: infrastructurePath()),
            needles: ['{{ class }}', '{{ namespace }}']
        ))->toBeFalse()
            ->and(value: Str::contains(
                haystack: fileGet(relativeFileName: $fileContract),
                needles: ['{{ class }}', '{{ namespace }}']
            ))->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate Factory Concrete and Interface with force option')
    ->tap(function () {
        $fileContract = '/Shared/Contracts/Database/Factories/UserFactory.php';
        $fileConcrete = '/User/Database/Factories/UserFactory.php';

        expect(value: fileExists(relativeFileName: $fileContract))->toBeFalse()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:factory UserFactory User');

        expect(value: fileExists(relativeFileName: $fileContract))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeTrue()
            ->and(value: Str::contains(
                haystack: Artisan::output(),
                needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
            ))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:factory UserFactory User --force');

        expect(value: fileExists(relativeFileName: $fileContract))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        expect(
            value: Str::contains(
                haystack: fileGet(relativeFileName: $fileConcrete, prefix: infrastructurePath()),
                needles: ['{{ class }}', '{{ namespace }}']
            )
        )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileContract),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'cannot generate the Factory, if the Factory already exists')
    ->tap(function () {
        $fileContract = '/Shared/Contracts/Database/Factories/UserFactory.php';
        $fileConcrete = '/User/Database/Factories/UserFactory.php';

        expect(value: fileExists(relativeFileName: $fileContract))->toBeFalse()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:factory UserFactory User');

        expect(value: fileExists(relativeFileName: $fileContract))->toBeTrue()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        Artisan::call(command: 'infrastructure:make:factory UserFactory User');

        expect(value: fileExists(relativeFileName: $fileContract))->toBeFalse()
            ->and(value: fileExists(relativeFileName: $fileConcrete, prefix: infrastructurePath()))->toBeFalse();
    })
    ->group('commands')
    ->throws(exception: FileAlreadyExistException::class);
