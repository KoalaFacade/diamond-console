<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate factory concrete and interface',
    closure: function () {
        $factoryName = 'TestFactory';
        $domainName = 'Test';

        $factoryInterfacePath = basePath() . domainPath() . '/Shared/' . $domainName . '/Models' . '/Contracts/' . $factoryName . 'Contract' . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryInterfacePath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName);

        expect(value: File::exists(path: $factoryInterfacePath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Interface']
                )
            )->toBeTrue();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName);

        expect(value:
            Str::contains(
                haystack: Artisan::output(),
                needles: 'is already exists at'
            )
        );

        File::delete([$factoryInterfacePath, $factoryConcretePath]);
    }
)->group('commands');

it(
    description: 'can generate factory concrete and interface with force option',
    closure: function () {
        $factoryName = 'TestFactory';
        $domainName = 'Test';

        $factoryInterfacePath = basePath() . domainPath() . '/Shared/' . $domainName . '/Models' . '/Contracts/' . $factoryName . 'Contract' . '.php';
        $factoryConcretePath = basePath() . infrastructurePath() . '/' . $domainName . '/Database' . '/Factories/' . $factoryName . '.php';

        expect(value: File::exists(path: $factoryInterfacePath))->toBeFalse()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeFalse();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName);

        expect(value: File::exists(path: $factoryInterfacePath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Interface']
                )
            )->toBeTrue();

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName);

        expect(value:
            Str::contains(
                haystack: Artisan::output(),
                needles: 'is already exists at'
            )
        );

        Artisan::call(command: 'diamond:factory ' . $factoryName . ' ' . $domainName . ' --force');

        expect(value: File::exists(path: $factoryInterfacePath))->toBeTrue()
            ->and(value: File::exists(path: $factoryConcretePath))->toBeTrue()
            ->and(value:
                Str::contains(
                    haystack: Artisan::output(),
                    needles: ['Succeed generate Factory concrete', 'Succeed generate Factory Interface']
                )
            )->toBeTrue();

        File::delete([$factoryInterfacePath, $factoryConcretePath]);
    }
)->group('commands');
