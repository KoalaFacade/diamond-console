<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Listener class')
    ->tap(function () {
        $fileName = '/Listeners/PostListener.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:listener PostListener Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Listener class with separator')
    ->tap(function () {
        $fileName = '/Listeners/Foo/Bar.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:listener Foo/Bar Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Listener class with Event option')
    ->tap(function () {
        $fileName = '/Listeners/PostListener.php';
        $eventName = '/Events/PostEvent.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:listener PostListener Post --event=PostEvent');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue();
        expect(
            value: Str::contains(
                haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                needles: ['{{ class }}', '{{ namespace }}']
            )
        )->toBeFalse()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $eventName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ event }}', '{{ eventNamespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Listener class')
    ->tap(function () {
        $fileName = '/Listeners/PostListener.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:listener PostListener Post');
        Artisan::call(command: 'infrastructure:make:listener PostListener Post --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Listener, if the Listener already exists')
    ->tap(function () {
        $fileName = '/Listeners/PostListener.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:listener PostListener Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:listener PostListener Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
