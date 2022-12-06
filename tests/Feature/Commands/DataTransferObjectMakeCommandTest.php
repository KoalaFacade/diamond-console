<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';

        expect(fileExists($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:dto PostData Post');

        expect(fileExists($fileName))->toBeTrue();

        $dtoFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $dtoFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists DTO')
    ->tap(function () {
        $fileName = '/Post/DataTransferObjects/PostData.php';
        expect(fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:dto PostData Post');
        Artisan::call(command: 'domain:make:dto PostData Post --force');

        expect(fileExists(relativeFileName: $fileName))->toBeTrue();

        $dtoFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $dtoFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the DTO, if the DTO already exists')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:dto PostData Post');
        Artisan::call(command: 'domain:make:dto PostData Post');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
