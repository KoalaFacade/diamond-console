<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');

        expect(filePresent($fileName))->toBeTrue();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        expect(filePresent(fileName: '/Post/DataTransferObjects/StoreUserAction.php'))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:dto PostData Post');
        Artisan::call(command: 'diamond:dto PostData Post --force');

        expect(filePresent(fileName: '/Post/DataTransferObjects/PostData.php'))->toBeTrue();
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
