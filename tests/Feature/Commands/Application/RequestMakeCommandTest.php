<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Request class')
    ->tap(function () {
        $fileName = 'Requests/StoreUserRequest.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:request StoreUserRequest User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Request class with separator')
    ->tap(function () {
        $fileName = 'Requests/Foo/BarRequest.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:request Foo/BarRequest User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Request class')
    ->tap(function () {
        $fileName = 'Requests/StoreUserRequest.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:request StoreUserRequest User');
        Artisan::call(command: 'application:make:request StoreUserRequest User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Request, if the Request already exists')
    ->tap(function () {
        $fileName = 'Requests/StoreUserRequest.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:request StoreUserRequest User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue();

        Artisan::call(command: 'application:make:request StoreUserRequest User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
