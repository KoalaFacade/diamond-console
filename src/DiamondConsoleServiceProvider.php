<?php

namespace KoalaFacade\DiamondConsole;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use SplFileInfo;

class DiamondConsoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishers();

        $this->commands(commands: $this->diamondCommands());
    }

    public function register(): void
    {
        $this->mergeConfigFrom(path: __DIR__ . '/../config/diamond.php', key: 'diamond');
    }

    protected function registerPublishers(): void
    {
        $this->publishes([
            __DIR__ . '/../config/diamond.php' => config_path(path: 'diamond.php'),
        ], groups: 'config');
    }

    /**
     * @return array<string>
     */
    public function diamondCommands(): array
    {
        $fileSystem = new Filesystem();

        $commandFiles = $fileSystem->files(directory: __DIR__ . '/Commands');

        return Arr::map(
            array: $commandFiles,
            callback: fn (SplFileInfo $file) => Str::of(string: $file->getBasename(suffix: '.php'))
                ->start(prefix: 'KoalaFacade\\DiamondConsole\\Commands\\')
                ->toString()
        );
    }
}
