<?php

namespace KoalaFacade\DiamondConsole;

use Illuminate\Support\ServiceProvider;

class DiamondConsoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(path: __DIR__.'/../config/config.php', key: 'diamond');
    }
}
