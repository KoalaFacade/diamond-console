<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Action class')
    ->tap(function () {
        $fileName = '/Actions/StoreUserAction.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Action class with separator')
    ->tap(function () {
        $fileName = '/Actions/Foo/BarAction.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:action Foo/BarAction User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Action class')
    ->tap(function () {
        $fileName = '/Actions/StoreUserAction.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:action StoreUserAction User');
        Artisan::call(command: 'domain:make:action StoreUserAction User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Action, if the Action already exists')
    ->tap(function () {
        $fileName = '/Actions/StoreUserAction.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue();

        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
