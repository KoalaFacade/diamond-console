<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate Observer')
    ->tap(function () {
        $fileName = '/User/Database/Observers/UserObserver.php';

        expect(value:fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('command');

it(description: 'can generate Observer with force option')
    ->tap(function () {
        $fileName = '/User/Database/Observers/UserObserver.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');
        Artisan::call(command: 'infrastructure:make:observer UserObserver User --force');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('command');

it(description: 'cannot generate the Observer, if the Observer already exists')
    ->tap(function () {
        $fileName = '/User/Database/Observers/UserObserver.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User ');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('command')
    ->throws(exception: FileAlreadyExistException::class);
