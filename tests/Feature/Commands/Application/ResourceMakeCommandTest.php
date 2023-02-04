<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Resource class')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Resources/User/UserResource.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:resource UserResource User --model=User');

        expect(value: File::exists(path: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Resource class with separator')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Resources/User/Foo/BarResource.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:resource Foo/BarResource User --model=User');

        expect(value: File::exists(path: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Resource class')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Resources/User/UserResource.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:resource UserResource User --model=User');
        Artisan::call(command: 'application:make:resource UserResource User --model=User --force');

        expect(value: File::exists(path: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Resource, if the Resource already exists')
    ->tap(function () {
        $fileName = app_path(path: 'Http/Resources/User/UserResource.php');

        expect(value: File::exists(path: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'application:make:resource UserResource User --model=User');

        expect(value: File::exists(path: $fileName))->toBeTrue();

        Artisan::call(command: 'application:make:resource UserResource User --model=User');

        expect(value: File::exists(path: $fileName))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
