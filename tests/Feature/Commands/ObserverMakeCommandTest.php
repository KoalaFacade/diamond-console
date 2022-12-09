<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(description: 'can generate observer')
    ->tap(function () {
        $nameArgument = 'UserObserver';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Observers/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');

        expect(File::exists(path: $generatedFilePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $generatedFilePath),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command');

it(description: 'can generate observer with force option')
    ->tap(function () {
        $nameArgument = 'UserObserver';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Observers/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');
        Artisan::call(command: 'infrastructure:make:observer UserObserver User --force');

        expect(File::exists(path: $generatedFilePath))->toBeTrue()
            ->and(
                value: Str::contains(
                    haystack: File::get(path: $generatedFilePath),
                    needles: ['{{ namespace }}', '{{ class }}']
                )
            )->toBeFalse();

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command');

it(description: 'cannot generate the observer, if the observer already exists')
    ->tap(function () {
        $nameArgument = 'UserObserver';
        $domainArgument = 'User';

        $generatedFilePath = basePath() . infrastructurePath() . '/' . $domainArgument . '/Database/Observers/' . $nameArgument . '.php';

        expect(value: File::exists(path: $generatedFilePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User');

        expect(File::exists(path: $generatedFilePath))->toBeTrue();

        Artisan::call(command: 'infrastructure:make:observer UserObserver User ');

        File::delete(paths: [$generatedFilePath]);
    })
    ->group('command')
    ->throws(exception: FileAlreadyExistException::class);
