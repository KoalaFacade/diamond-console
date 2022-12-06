<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it('can generate new provider file')
    ->tap(function () {
        $relativeFileName = '/User/Providers/FactoryServiceProvider.php';

        expect(fileExists($relativeFileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User');

        expect(fileExists($relativeFileName, prefix: infrastructurePath()))
            ->toBeTrue()
            ->and(Str::contains(haystack: $relativeFileName, needles: [
                '{{ class }}',
                '{{ namespace }}'
            ]))
            ->toBeFalse();
    });

it('can generate new provider file with force')
    ->tap(function () {
        $relativeFileName = '/User/Providers/FactoryServiceProvider.php';

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User');
        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User --force');

        expect(fileExists($relativeFileName, prefix: infrastructurePath()))->toBeTrue();
    });

it(description: 'file already exist')
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class)
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User');
        Artisan::call(command: 'infrastructure:make:provider FactoryServiceProvider User');
    });