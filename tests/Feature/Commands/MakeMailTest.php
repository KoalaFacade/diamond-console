<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

it(
    description: 'can force generate exists mail class',
    closure: function () {
        $this->assertFalse(File::exists(base_path('src/Infrastructure/User/Mail/UserApproved.php')));

        Artisan::call('diamond:install');
        Artisan::call('diamond:mail UserApproved User');
        Artisan::call('diamond:mail UserApproved User');

        $this->assertTrue(Str::contains(Artisan::output(), 'UserApproved.php already exists.'));

        Artisan::call('diamond:mail UserApproved User --force');

        $this->assertTrue(File::exists(base_path('src/Infrastructure/User/Mail/UserApproved.php')));

        $filesystem = new Filesystem;
        $filesystem->deleteDirectory(base_path('src'));
    }
)->group('commands');
