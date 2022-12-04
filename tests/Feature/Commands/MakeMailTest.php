<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new mail class')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:mail UserApproved User');

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeTrue();
    })
    ->group('commands');

it(description: 'can force generate exists mail class')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:mail UserApproved User');
        Artisan::call(command: 'diamond:mail UserApproved User --force');

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeTrue();
    })
    ->group(groups: 'commands');

it(description: 'file already exist')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:mail UserApproved User');
        Artisan::call(command: 'diamond:mail UserApproved User');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
