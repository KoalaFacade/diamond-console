<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Builder class')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/UserBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:builder UserBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Builder class with Model option')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/PostBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'domain:make:builder PostBuilder Post --model=Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post'),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Model class with separator')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/Foo/barBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:builder Foo/barBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Builder class')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/UserBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:builder UserBuilder User');
        Artisan::call(command: 'domain:make:builder UserBuilder User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User'),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Builder class with Model option')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/PostBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'domain:make:builder PostBuilder Post --model=Post');
        Artisan::call(command: 'domain:make:builder PostBuilder Post --model=Post --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post'),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Builder, if the Builder already exists')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/UserBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'domain:make:builder UserBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeTrue();

        Artisan::call(command: 'domain:make:builder UserBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User'))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);

it(description: 'cannot generate the Builder with Model Post, if the Builder already exists')
    ->tap(function () {
        $fileName = '/Shared/Models/Builders/PostBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();

        Artisan::call(command: 'domain:make:builder PostBuilder Post --model=Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeTrue();

        Artisan::call(command: 'domain:make:builder PostBuilder Post --model=Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post'))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
