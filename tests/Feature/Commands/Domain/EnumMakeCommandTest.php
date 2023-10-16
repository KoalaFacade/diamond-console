<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Enum')
    ->tap(function () {
        $fileName = '/Enums/PostStatus.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Enum with separator')
    ->tap(function () {
        $fileName = '/Enums/Foo/Bar.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'domain:make:enum Foo/Bar Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Enum')
    ->tap(function () {
        $fileName = '/Enums/PostStatus.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Enum, if the Enum already exists')
    ->tap(function () {
        $fileName = '/Enums/PostStatus.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue();

        Artisan::call(command: 'domain:make:enum PostStatus Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
