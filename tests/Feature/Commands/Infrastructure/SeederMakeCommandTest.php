<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate Seeder')
    ->tap(function () {
        $fileName = '/Database/Seeders/UserSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();
    })
    ->group('command');

it(description: 'can generate Seeder with separator')
    ->tap(function () {
        $fileName = '/Database/Seeders/Foo/BarSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder Foo/BarSeeder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();
    })
    ->group('command');

it(description: 'can generate Seeder with force option')
    ->tap(function () {
        $fileName = '/Database/Seeders/UserSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');
        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();
    })
    ->group('command');

it(description: 'cannot generate the Seeder, if the Seeder already exists')
    ->tap(function () {
        $fileName = '/Database/Seeders/UserSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User ');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();
    })
    ->group('command')
    ->throws(exception: FileAlreadyExistException::class);
