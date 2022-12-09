<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:data-transfer-object PostData Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:data-transfer-object PostData Post');
        Artisan::call(command: 'domain:make:data-transfer-object PostData Post --force');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the DTO, if the DTO already exists')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:data-transfer-object PostData Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        Artisan::call(command: 'domain:make:data-transfer-object PostData Post');

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
