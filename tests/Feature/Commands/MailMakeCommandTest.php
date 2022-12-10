<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Mail class')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group('commands');

it(description: 'can force generate exists Mail class')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User --force');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Mail, if the Mail already exists')
    ->tap(function () {
        $fileName = '/User/Mail/UserApproved.php';

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(value: fileExists(relativeFileName: $fileName, prefix: infrastructurePath()))->toBeFalse();

        fileDelete(paths: fileGet(relativeFileName: $fileName, prefix: infrastructurePath()));
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
