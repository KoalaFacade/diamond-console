<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Enum')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands');

it(description: 'can generate new Enum with separator')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        $fileName = '/Post/Enums/Foo/Bar.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum Foo/Bar Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Enum')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum PostStatus Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post --force');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Enum, if the Enum already exists')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<'), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
