<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it('can generate new Service Provider file')
    ->tap(function () {
        $fileName = '/User/Providers/FactoryServiceProvider.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:provider Factory User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                Str::contains(
                    haystack: $fileName,
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group(groups: 'commands');

it('can generate new Service Provider file with force')
    ->tap(function () {
        $fileName = '/User/Providers/FactoryServiceProvider.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:provider Factory User');
        Artisan::call(command: 'infrastructure:make:provider Factory User --force');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                Str::contains(
                    haystack: $fileName,
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Service Provider, if the Service Provider already exists')
    ->tap(function () {
        $fileName = '/User/Providers/FactoryServiceProvider.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
