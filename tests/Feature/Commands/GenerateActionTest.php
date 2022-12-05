<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new action class')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(filePresent(fileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:action StoreUserAction User');

        expect(filePresent(fileName: $fileName))
            ->toBeTrue();

        $actionFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $actionFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists action class')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(filePresent(fileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:action StoreUserAction User');
        Artisan::call(command: 'diamond:action StoreUserAction User --force');

        expect(filePresent(fileName: $fileName))
            ->toBeTrue();

        $actionFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $actionFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'file already exist')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:action StoreUserAction User');
        Artisan::call(command: 'diamond:action StoreUserAction User');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
