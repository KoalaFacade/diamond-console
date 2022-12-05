<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:dto PostData Post');

        expect(filePresent($fileName))->toBeTrue();

        $dtoFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $dtoFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';
        expect(filePresent(fileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:dto PostData Post');
        Artisan::call(command: 'domain:make:dto PostData Post --force');

        expect(filePresent(fileName: $fileName))->toBeTrue();

        $dtoFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $dtoFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'file already exist')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:dto PostData Post');
        Artisan::call(command: 'domain:make:dto PostData Post');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
