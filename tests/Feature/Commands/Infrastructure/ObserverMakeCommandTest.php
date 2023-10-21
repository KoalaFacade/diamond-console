<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate Observer')
    ->tap(function () {
        $fileName = '/Database/Observers/UserObserver.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();
    })
    ->group('command');

it(description: 'can generate Observer with separator')
    ->tap(function () {
        $fileName = '/Database/Observers/Foo/BarObserver.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer Foo/BarObserver User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();
    })
    ->group('command');

it(description: 'can generate Observer with force option')
    ->tap(function () {
        $fileName = '/Database/Observers/UserObserver.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');
        Artisan::call(command: 'infrastructure:make:observer UserObserver User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();
    })
    ->group('command');

it(description: 'cannot generate the Observer, if the Observer already exists')
    ->tap(function () {
        $fileName = '/Database/Observers/UserObserver.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User ');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group('command')
    ->throws(exception: FileAlreadyExistException::class);
