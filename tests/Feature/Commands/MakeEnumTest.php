<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new enum')
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:enum PostStatus Post');

        expect(filePresent($fileName))->toBeTrue();
    })
    ->group(groups: 'commands')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<='), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION);

it(description: 'can force generate exists enum')
    ->tap(function () {
        $fileName = '/Post/Enums/PostStatus.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:enum PostStatus Post');
        Artisan::call(command: 'diamond:enum PostStatus Post --force');

        expect(filePresent($fileName))->toBeTrue();
    })
    ->group(groups: 'commands')
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<='), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION);

it(description: 'file already exist')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:enum PostStatus Post');
        Artisan::call(command: 'diamond:enum PostStatus Post');
    })
    ->skip(version_compare(PHP_VERSION, '8.1.0', '<='), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION)
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);