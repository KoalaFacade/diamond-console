<?php

namespace KoalaFacade\DiamondConsole;

use Illuminate\Support\ServiceProvider;

class DiamondConsoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishables();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(path: __DIR__.'/../config/diamond.php', key: 'diamond');
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__.'/../config/diamond.php' => config_path(path: 'diamond.php'),
        ], 'config');
    }
}
