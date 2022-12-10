<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new action class')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(fileExists(relativeFileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action StoreUserAction User');

        expect(fileExists(relativeFileName: $fileName))
            ->toBeTrue();

        $actionFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $actionFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new action class with nested separator')
    ->tap(function () {
        $fileName = '/User/Actions/Foo/Bar/StoreFooAction.php';

        expect(fileExists(relativeFileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action Foo/Bar/StoreFooAction User');

        expect(fileExists(relativeFileName: $fileName))
            ->toBeTrue();

        $actionFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $actionFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists action class')
    ->tap(function () {
        $fileName = '/User/Actions/StoreUserAction.php';

        expect(fileExists(relativeFileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action StoreUserAction User');
        Artisan::call(command: 'domain:make:action StoreUserAction User --force');

        expect(fileExists(relativeFileName: $fileName))
            ->toBeTrue();

        $actionFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $actionFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the action, if the action already exists')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:action StoreUserAction User');
        Artisan::call(command: 'domain:make:action StoreUserAction User');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
