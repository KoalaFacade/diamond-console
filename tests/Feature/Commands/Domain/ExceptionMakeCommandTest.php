<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Exception class')
    ->tap(function () {
        $fileName = '/Exceptions/StoreUserException.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:exception StoreUserException User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Exception class with render function')
    ->tap(function () {
        $fileName = '/Exceptions/StoreUserException.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:exception StoreUserException User --render');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Exception class with separator')
    ->tap(function () {
        $fileName = '/Exceptions/Foo/BarException.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:exception Foo/BarException User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Exception class')
    ->tap(function () {
        $fileName = '/Exceptions/StoreUserException.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:exception StoreUserException User');
        Artisan::call(command: 'domain:make:exception StoreUserException User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Exception, if the Exception already exists')
    ->tap(function () {
        $fileName = '/Exceptions/StoreUserException.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:exception StoreUserException User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue();

        Artisan::call(command: 'domain:make:exception StoreUserException User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
