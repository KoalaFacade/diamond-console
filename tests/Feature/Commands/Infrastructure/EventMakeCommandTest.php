<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Event class')
    ->tap(function () {
        $fileName = '/Events/PostEvent.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:event PostEvent Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Event class with separator')
    ->tap(function () {
        $fileName = '/Events/Foo/Bar.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:event Foo/Bar Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Event class')
    ->tap(function () {
        $fileName = '/Events/PostEvent.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:event PostEvent Post');
        Artisan::call(command: 'infrastructure:make:event PostEvent Post --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Event, if the Event already exists')
    ->tap(function () {
        $fileName = '/Events/PostEvent.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:event PostEvent Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:event PostEvent Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
