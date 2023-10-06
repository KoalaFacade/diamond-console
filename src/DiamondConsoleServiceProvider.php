<?php

namespace KoalaFacade\DiamondConsole;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

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
        $commandDirectories = ['Application', 'Domain', 'Infrastructure'];
        $commandPath = __DIR__ . '/Commands';

        $fileSystem = new Filesystem();

        $commandFiles = $fileSystem->files(directory: $commandPath);

        $generalCommands = Arr::map(
            array: $commandFiles,
            callback: fn (SplFileInfo $file) => $this->resolveCommandNamespace(file: $file)
        );

        $domainDrivenCommands = Arr::map(
            array: $commandDirectories,
            callback: fn (string $directory) => Arr::map(
                array: $fileSystem->files(directory: $commandPath . '/' . $directory),
                callback: fn (SplFileInfo $file) => $this->resolveCommandNamespace(file: $file, directory: $directory)
            )
        );

        return Arr::flatten(array: array_merge($generalCommands, $domainDrivenCommands));
    }

    protected function resolveCommandNamespace(SplFileInfo $file, string $directory = null): string
    {
        return Str::of(string: $file->getFilenameWithoutExtension())
            ->prepend(values: $directory ? "$directory\\" : '')
            ->prepend(values: __NAMESPACE__ . '\\Commands\\');
    }
}
