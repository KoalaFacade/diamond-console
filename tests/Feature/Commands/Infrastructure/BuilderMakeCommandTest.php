<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Builder class')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/UserBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:builder UserBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Builder class with Model option')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/PostBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:builder PostBuilder Post --model=Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Model class with separator')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/Foo/barBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:builder Foo/barBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Builder class')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/UserBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:builder UserBuilder User');
        Artisan::call(command: 'infrastructure:make:builder UserBuilder User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists Builder class with Model option')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/PostBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'infrastructure:make:builder PostBuilder Post --model=Post');
        Artisan::call(command: 'infrastructure:make:builder PostBuilder Post --model=Post --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}', '{{ model }}', '{{ modelNamespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Builder, if the Builder already exists')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/UserBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:builder UserBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:builder UserBuilder User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);

it(description: 'cannot generate the Builder with Model opPost, if the Builder already exists')
    ->tap(function () {
        $fileName = '/Database/Models/Builders/PostBuilder.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:builder PostBuilder Post --model=Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:builder PostBuilder Post --model=Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
