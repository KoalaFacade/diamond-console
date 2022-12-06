<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

it(
    description: 'can generate factory concrete and interface',
    closure: function () {
        $factoryName = 'TestFactory';
        $domainName = 'Test';

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:factory ' . $factoryName . ' ' . $domainName);

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        $factoryContractFile = File::get(path: $factoryContractPath);
        $factoryConcreteFile = File::get(path: $factoryConcretePath);

        expect(value: Str::contains(haystack: $factoryContractFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();
        expect(value: Str::contains(haystack: $factoryConcreteFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();

        File::delete([$factoryContractPath, $factoryConcretePath]);
    }
)->group('commands');

it(
    description: 'can generate factory concrete and interface with force option',
    closure: function () {
        $factoryName = 'TestFactory';
        $domainName = 'Test';

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:factory ' . $factoryName . ' ' . $domainName);

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        Artisan::call(command: 'infrastructure:make:factory ' . $factoryName . ' ' . $domainName . ' --force');

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        $factoryContractFile = File::get(path: $factoryContractPath);
        $factoryConcreteFile = File::get(path: $factoryConcretePath);

        expect(value: Str::contains(haystack: $factoryContractFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse()
            ->and(value: Str::contains(haystack: $factoryConcreteFile, needles: ['{{ class }}', '{{ namespace }}']))->toBeFalse();

        File::delete([$factoryContractPath, $factoryConcretePath]);
    }
)->group('commands');

it(
    description: 'cannot generate the Factory, if the Factory already exists',
    closure: function () {
        $factoryName = 'TestFactory';
        $domainName = 'Test';

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'infrastructure:make:factory ' . $factoryName . ' ' . $domainName);

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        Artisan::call(command: 'infrastructure:make:factory ' . $factoryName . ' ' . $domainName);

        File::delete([$factoryContractPath, $factoryConcretePath]);
    }
)
    ->group('commands')
    ->throws(exception: FileAlreadyExistException::class);
