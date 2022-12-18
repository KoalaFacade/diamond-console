<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Enums\Layer;

uses(Tests\TestCase::class)
    ->beforeEach(function () {
        $fileSystem = new FileSystem();

        $fileSystem->deleteDirectory(basePath());
        $fileSystem->cleanDirectory(base_path(path: applicationPath() . '/Http/Requests'));
        $fileSystem->cleanDirectory(base_path(path: 'database/migrations'));
    })
    ->in(__DIR__ . '/Feature');

uses(Tests\TestCase::class)
    ->in(__DIR__ . '/Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function basePath(): string
{
    /* @var string $path */
    $path = config(key: 'diamond.base_directory');

    return base_path($path);
}

function resolvePathForStructure(string $key): string
{
    /** @var string $path */
    $path = config(key: 'diamond.structures.' . $key);

    return $path;
}

function domainPath(): string
{
    return resolvePathForStructure(key: Layer::domain->name);
}

function infrastructurePath(): string
{
    return resolvePathForStructure(key: Layer::infrastructure->name);
}

function applicationPath(): string
{
    return resolvePathForStructure(key: Layer::application->name);
}

function fileExists(string $relativeFileName, null | string $prefix = null): bool
{
    return File::exists(
        path: basePath() . ($prefix ?? domainPath()) . Str::start($relativeFileName, prefix: '/')
    );
}

function fileGet(string $relativeFileName, null | string $prefix = null): string
{
    return File::get(
        path: basePath() . ($prefix ?? domainPath()) . Str::start($relativeFileName, prefix: '/')
    );
}
