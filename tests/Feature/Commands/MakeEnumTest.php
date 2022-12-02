<?php

namespace Tests\Feature\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it(
    description: 'can generate new enum',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        if (File::exists(base_path("$basePath/$domainPath/Post/Enums/PostStatus.php"))) {
            unlink(base_path("$basePath/$domainPath/Post/Enums/PostStatus.php"));
        }

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/Post/Enums/PostStatus.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:enum PostStatus Post');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/Post/Enums/PostStatus.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands')->skip(version_compare(PHP_VERSION, '8.1.0', '<='), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION);

it(
    description: 'can force generate exists enum',
    closure: function () {
        $basePath = config(key: 'diamond.base_directory');
        $domainPath = config(key: 'diamond.structures.domain');

        $this->assertFalse(File::exists(base_path("$basePath/$domainPath/Post/Enums/StoreUserAction.php")));

        Artisan::call(command: 'diamond:install');
        Artisan::call(command: 'diamond:enum PostStatus Post');
        Artisan::call(command: 'diamond:enum PostStatus Post');

        $this->assertTrue(Str::contains(Artisan::output(), needles: 'PostStatus.php already exists.'));

        Artisan::call(command: 'diamond:enum PostStatus Post --force');

        $this->assertTrue(File::exists(base_path("$basePath/$domainPath/Post/Enums/PostStatus.php")));

        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(base_path($basePath));
    }
)->group('commands')->skip(version_compare(PHP_VERSION, '8.1.0', '<='), 'code contains php 8.1 feature cause this test run in ' . PHP_VERSION);
