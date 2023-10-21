<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it('can generate new Service Provider file')
    ->tap(function () {
        $fileName = '/Providers/FactoryServiceProvider.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:provider Factory User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                Str::contains(
                    haystack: $fileName,
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it('can generate new Service Provider file with force')
    ->tap(function () {
        $fileName = '/Providers/FactoryServiceProvider.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:provider Factory User');
        Artisan::call(command: 'application:make:provider Factory User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue()
            ->and(
                Str::contains(
                    haystack: $fileName,
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Service Provider, if the Service Provider already exists')
    ->tap(function () {
        $fileName = '/Providers/FactoryServiceProvider.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'application:make:provider FactoryServiceProvider User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeTrue();

        Artisan::call(command: 'application:make:provider FactoryServiceProvider User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: applicationPath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
