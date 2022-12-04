<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $filePresent = fn () => File::exists(
            path: baseDirectory() . domainPath() . '/Post/DataTransferObjects/PostData.php'
        );

        expect($filePresent())->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');

        expect($filePresent())->toBeTrue();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        $filePresent = fn (string $suffix) => File::exists(
            path: baseDirectory(). domainPath() . $suffix
        );

        expect($filePresent(suffix: '/Post/DataTransferObjects/StoreUserAction.php'))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');
        Artisan::call(command: 'diamond:dto PostData Post --force');

        expect($filePresent(suffix: '/Post/DataTransferObjects/PostData.php'))->toBeTrue();
    })
    ->group(groups: 'commands');

it(description: 'file already exist')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');
        Artisan::call(command: 'diamond:dto PostData Post');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
