<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $fileName = '/DataTransferObjects/PostData.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new DTO with separator')
    ->tap(function () {
        $fileName = '/DataTransferObjects/Foo/BarData.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'application:make:data-transfer-object Foo/BarData Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )
            ->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        $fileName = '/DataTransferObjects/PostData.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the DTO, if the DTO already exists')
    ->tap(function () {
        $fileName = '/DataTransferObjects/PostData.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeFalse();

        Artisan::call(command: 'domain:install Post');
        Artisan::call(command: 'application:make:data-transfer-object PostData Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeTrue();

        Artisan::call(command: 'application:make:data-transfer-object PostData Post');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'Post', prefix: applicationPath()))->toBeTrue();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
