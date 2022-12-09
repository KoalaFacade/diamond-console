<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate Seeder')
    ->tap(function () {
        $fileName = '/User/Database/Seeders/UserSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('command');

it(description: 'can generate Seeder with force option')
    ->tap(function () {
        $fileName = '/User/Database/Seeders/UserSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');
        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User --force');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('command');

it(description: 'cannot generate the Seeder, if the Seeder already exists')
    ->tap(function () {
        $fileName = '/User/Database/Seeders/UserSeeder.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User ');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('command')
    ->throws(exception: FileAlreadyExistException::class);
