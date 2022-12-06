<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new mail class')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeTrue();

        $mailFile = File::get(path: basePath() . infrastructurePath() . $fileName);

        expect(value: Str::contains(haystack: $mailFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists mail class')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User --force');

        expect(filePresent($fileName, prefix: infrastructurePath()))->toBeTrue();

        $mailFile = File::get(path: basePath() . infrastructurePath() . $fileName);

        expect(value: Str::contains(haystack: $mailFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Mail, if the Mail already exists')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
