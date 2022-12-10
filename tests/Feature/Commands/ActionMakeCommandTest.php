<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Action class')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group('commands');

it(description: 'can force generate exists Action class')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action StoreUserAction User');
        Artisan::call(command: 'domain:make:action StoreUserAction User --force');

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

it(description: 'cannot generate the Action, if the Action already exists')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName));
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
