<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Mail class')
    ->tap(function () {
        $fileName = '/Mails/UserApproved.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Mail class with separator')
    ->tap(function () {
        $fileName = '/Mails/Foo/Bar.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:mail Foo/Bar User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Mail class')
    ->tap(function () {
        $fileName = '/Mails/UserApproved.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User --force');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Mail, if the Mail already exists')
    ->tap(function () {
        $fileName = '/Mails/UserApproved.php';

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();

        Artisan::call(command: 'domain:install User');
        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:mail UserApproved User');

        expect(value: fileExists(relativeFileName: $fileName, domain: 'User', prefix: infrastructurePath()))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
