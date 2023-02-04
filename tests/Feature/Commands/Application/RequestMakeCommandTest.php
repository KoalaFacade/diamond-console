<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Request class')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Requests/User/StoreUserRequest.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:request StoreUserRequest User');

        expect(value: File::exists(path: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Request class with separator')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Requests/User/Foo/BarRequest.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:request Foo/BarRequest User');

        expect(value: File::exists(path: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Request class')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Requests/User/StoreUserRequest.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:request StoreUserRequest User');
        Artisan::call(command: 'application:make:request StoreUserRequest User --force');

        expect(value: File::exists(path: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Request, if the Request already exists')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Requests/User/StoreUserRequest.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:request StoreUserRequest User');

        expect(value: File::exists(path: $fileName))->toBeTrue();

        Artisan::call(command: 'application:make:request StoreUserRequest User');

        expect(value: File::exists(path: $fileName))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
