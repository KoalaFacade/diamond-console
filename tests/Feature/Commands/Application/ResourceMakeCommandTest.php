<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Resource class')
    ->tap(function () {
        $fileName = 'Resources/UserResource.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:resource UserResource User --model=User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Resource class with separator')
    ->tap(function () {
        $fileName = 'Resources/Foo/BarResource.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:resource Foo/BarResource User --model=User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Resource class')
    ->tap(function () {
        $fileName = 'Resources/UserResource.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:resource UserResource User --model=User');
        Artisan::call(command: 'application:make:resource UserResource User --model=User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Resource, if the Resource already exists')
    ->tap(function () {
        $fileName = 'Resources/UserResource.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:resource UserResource User --model=User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue();

        Artisan::call(command: 'application:make:resource UserResource User --model=User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
