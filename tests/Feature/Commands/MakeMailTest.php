<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it(
    description: 'can generate new mail class',
    closure: function () {
        if (File::exists(base_path('src/Infrastructure/User/Mail/UserApproved.php'))) {
            unlink(base_path('src/Infrastructure/User/Mail/UserApproved.php'));
        }

        $this->assertFalse(File::exists(base_path('src/Infrastructure/User/Mail/UserApproved.php')));

        Artisan::call('diamond:install');
        Artisan::call('diamond:mail UserApproved User');

        $this->assertTrue(File::exists(base_path('src/Infrastructure/User/Mail/UserApproved.php')));

        $filesystem = new Filesystem;
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');
