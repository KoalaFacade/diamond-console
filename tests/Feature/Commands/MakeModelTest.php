<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

it(description: 'can generate new model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');

        expect(filePresent($fileName))->toBeTrue();
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists model class')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User');
        Artisan::call(command: 'diamond:model User User --force');

        expect(filePresent($fileName))->toBeTrue();
    })
    ->group(groups: 'commands');

it(description: 'can generate new model class with migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');

        expect(filePresent($fileName))->toBeTrue();

        $tableName = Str::snake('User');

        unlink(base_path(
            path: 'database/migrations/' . now()->format('Y_m_d_his') . '_' . $tableName . '.php'
        ));
    })
    ->group(groups: 'commands');

it(description: 'can force generate exists model class with migration')
    ->tap(function () {
        $fileName = '/Shared/User/Models/User.php';

        expect(filePresent($fileName))->toBeFalse();

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:model User User -m');
        Artisan::call(command: 'diamond:model User User -m --force');

        expect(filePresent($fileName))->toBeTrue();

        $tableName = Str::snake('User');

        $fileName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        unlink(base_path("database/migrations/$fileName"));
    })
    ->group(groups: 'commands');
