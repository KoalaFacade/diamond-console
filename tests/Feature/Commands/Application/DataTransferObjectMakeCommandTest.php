<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $filePath = base_path(path: applicationPath() . '/DataTransferObjects/Post/PostData.php');

        expect(value: File::exists(path: $filePath))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post');

        expect(value: File::exists(path: $filePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $filePath),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new DTO with separator')
    ->tap(function () {
        $filePath = base_path(path: applicationPath() . '/DataTransferObjects/Post/Foo/BarData.php');

        expect(value: File::exists(path: $filePath))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:data-transfer-object Foo/BarData Post');

        expect(value: File::exists(path: $filePath))
            ->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $filePath),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )
            ->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        $filePath = base_path(path: applicationPath() . '/DataTransferObjects/Post/PostData.php');

        expect(value: File::exists(path: $filePath))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post --force');

        expect(value: File::exists(path: $filePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $filePath),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the DTO, if the DTO already exists')
    ->tap(function () {
        $filePath = base_path(path: applicationPath() . '/DataTransferObjects/Post/PostData.php');

        expect(value: File::exists(path: $filePath))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post');

        expect(value: File::exists(path: $filePath))->toBeTrue();

        Artisan::call(command: 'application:make:data-transfer-object PostData Post');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
