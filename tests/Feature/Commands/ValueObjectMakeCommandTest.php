<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new Value Object class')
    ->tap(function () {
        $fileName = '/User/ValueObjects/ReferralCode.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:value-object ReferralCode User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can generate new Value Object class with separator')
    ->tap(function () {
        $fileName = '/User/ValueObjects/Foo/Bar.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:value-object Foo/Bar User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists Value Object class')
    ->tap(function () {
        $fileName = '/User/ValueObjects/ReferralCode.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:value-object ReferralCode User');
        Artisan::call(command: 'domain:make:value-object ReferralCode User --force');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: fileGet(relativeFileName: $fileName),
                    needles: ['{{ class }}', '{{ namespace }}']
                )
            )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Value Object, if the Value Object already exists')
    ->tap(function () {
        $fileName = '/User/ValueObjects/ReferralCode.php';

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:value-object ReferralCode User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeTrue();

        Artisan::call(command: 'domain:make:value-object ReferralCode User');

        expect(value: fileExists(relativeFileName: $fileName))->toBeFalse();
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
