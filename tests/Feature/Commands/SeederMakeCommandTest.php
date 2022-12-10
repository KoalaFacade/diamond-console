<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate seeder')
    ->tap(function () {
        $nameArgument = 'UserSeeder';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Seeders/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');

        expect(File::exists(path: $generatedFilePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $generatedFilePath),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command');

it(description: 'can generate seeder with separator')
    ->tap(function () {
        $nameArgument = 'Foo/BarSeeder';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Seeders/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder Foo/BarSeeder User');

        expect(File::exists(path: $generatedFilePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $generatedFilePath),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command');

it(description: 'can generate seeder with force option')
    ->tap(function () {
        $nameArgument = 'UserSeeder';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Seeders/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');
        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User --force');

        expect(File::exists(path: $generatedFilePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $generatedFilePath),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command');

it(description: 'cannot generate the Seeder, if the Seeder already exists')
    ->tap(function () {
        $nameArgument = 'UserSeeder';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Seeders/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User');

        expect(File::exists(path: $generatedFilePath))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:seeder UserSeeder User ');

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command')
    ->throws(exception: FileAlreadyExistException::class);
