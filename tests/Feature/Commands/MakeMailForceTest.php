<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it(
    description: 'can force generate exists mail class',
    closure: function () {
        Artisan::call('diamond:install');
        Artisan::call('diamond:mail UserApproved User');
        Artisan::call('diamond:mail UserApproved User --force');

        $this->assertTrue(File::exists(base_path('src/Infrastructure/User/Mail/UserApproved.php')));

        $filesystem = new Filesystem;
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');
