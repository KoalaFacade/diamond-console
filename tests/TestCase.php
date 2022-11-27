<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            'KoalaFacade\DiamondConsole\DiamondConsoleServiceProvider',
        ];
    }
}
