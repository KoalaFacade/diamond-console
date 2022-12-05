<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new enum')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(filePresent($fileName))->toBeTrue();

        $enumFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $enumFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists enum')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum PostStatus Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post --force');

        expect(filePresent($fileName))->toBeTrue();

        $enumFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $enumFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'file already exist')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum PostStatus Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
