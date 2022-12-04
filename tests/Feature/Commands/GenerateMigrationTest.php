<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(description: 'can generate new migration')
    ->tap(function () {
        $tableName = Str::snake(Str::pluralStudly('User'));

        $fileName = now()->format('Y_m_d_his') . '_create_' . $tableName . '_table.php';

        $this->assertFalse(File::exists(base_path("database/migrations/$fileName")));

        Artisan::call(command: 'diamond:migration user');

        $this->assertTrue(File::exists(base_path("database/migrations/$fileName")));

        unlink(base_path("database/migrations/$fileName"));
    })
    ->group(groups: 'commands');
