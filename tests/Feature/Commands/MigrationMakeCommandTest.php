<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(description: 'can generate new create Migration')
    ->tap(function () {
        $tableName = Str::snake('CreateUsersTable');

        $fileName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        $this->assertFalse(File::exists(base_path("database/migrations/$fileName")));

        Artisan::call(command: 'diamond:make:migration CreateUsersTable --create=users');

        $this->assertTrue(File::exists(base_path("database/migrations/$fileName")));

        $migrationFile = File::get(path: base_path("database/migrations/$fileName"));

        expect(
            value: Str::contains(
                haystack: $migrationFile,
                needles: ['{{ table_name }}']
            )
        )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new table Migration')
    ->tap(function () {
        $tableName = Str::snake('UpdateUsersTable');

        $fileName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        $this->assertFalse(File::exists(base_path("database/migrations/$fileName")));

        Artisan::call(command: 'diamond:make:migration UpdateUsersTable --table=users');

        $this->assertTrue(File::exists(base_path("database/migrations/$fileName")));

        $migrationFile = File::get(path: base_path("database/migrations/$fileName"));

        expect(
            value: Str::contains(
                haystack: $migrationFile,
                needles: ['{{ table_name }}']
            )
        )->toBeFalse();
    })
    ->group(groups: 'commands');

it(description: 'can generate new Migration')
    ->tap(function () {
        $tableName = Str::snake('UserPost');

        $fileName = now()->format('Y_m_d_his') . '_' . $tableName . '.php';

        $this->assertFalse(File::exists(base_path("database/migrations/$fileName")));

        Artisan::call(command: 'diamond:make:migration UserPost');

        $this->assertTrue(File::exists(base_path("database/migrations/$fileName")));
    })
    ->group(groups: 'commands');
