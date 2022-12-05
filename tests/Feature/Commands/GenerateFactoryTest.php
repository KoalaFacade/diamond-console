<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate factory concrete and interface',
    closure: function () {
        $factoryName = 'TestFactory';
        $domainName = 'Test';

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . 'Contract.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName);

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

        $factoryContractPath = basePath() . domainPath() . '/Shared/Contracts/Database/Factories/' . $factoryName . 'Contract.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryContractPath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName);

        expect(value: File::exists(path: $factoryContractPath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Contract']
                )
            )->toBeTrue();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName . ' --force');

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
