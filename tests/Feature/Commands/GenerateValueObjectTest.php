<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate new ValueObject class')
    ->tap(function () {
        $fileName = '/User/ValueObjects/RefferalCode.php';

        expect(filePresent(fileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:valueobject RefferalCode User');

        expect(filePresent(fileName: $fileName))
            ->toBeTrue();

        $valueObjectFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $valueObjectFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group('commands');

it(description: 'can force generate exists ValueObject class')
    ->tap(function () {
        $fileName = '/User/ValueObjects/RefferalCode.php';

        expect(filePresent(fileName: $fileName))
            ->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:valueobject RefferalCode User');
        Artisan::call(command: 'domain:make:valueobject RefferalCode User --force');

        expect(filePresent(fileName: $fileName))
            ->toBeTrue();

        $valueObjectFile = File::get(path: basePath() . domainPath() . $fileName);

        expect(value: Str::contains(haystack: $valueObjectFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'cannot generate the Value Object, if the Value Object already exists')
    ->tap(function () {
        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'domain:make:valueobject RefferalCode User');
        Artisan::call(command: 'domain:make:valueobject RefferalCode User');
    })
    ->group(groups: 'commands')
    ->throws(exception: FileAlreadyExistException::class);
