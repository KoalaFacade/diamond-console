<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Composer\ResolveComposerAutoLoaderAction;
use KoalaFacade\DiamondConsole\Enums\Layer;
use KoalaFacade\DiamondConsole\Support\Source;
use Throwable;

class InstallCommand extends Command
{
    protected $signature = 'diamond:install {--skip-refactor}';

    protected $description = 'Install the Domain Driven Structure in your project';

    /**
     * @throws Throwable
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating files to our project');

        $fileSystem = new Filesystem();

        $fileSystem->ensureDirectoryExists(path: $this->resolveBaseDirectoryPath());

        foreach ($this->resolveBaseStructures() as $structure) {
            $path = $this->resolveBaseDirectoryPath() . $structure;

            if ($fileSystem->exists(path: $path)) {
                $this->line(string: 'Skipping generate ' . $structure . ' , the base directory is exists');

                continue;
            }

            $fileSystem->makeDirectory(path: $path);
        }

        ResolveComposerAutoLoaderAction::resolve()->execute();

        $this->resolveRefactor();

        $this->info(string: 'Successfully generate base file');
    }

    protected function resolveBaseDirectoryPath(): string
    {
        return base_path(path: Source::resolveBasePath());
    }

    /**
     * @return array<string>
     */
    protected function resolveBaseStructures(): array
    {
        /** @var array<string> $structures */
        $structures = config(key: 'diamond.structures');

        return $structures;
    }

    protected function resolveInfrastructurePath(): string
    {
        return Layer::infrastructure->resolvePath(suffix: '/Laravel/Providers');
    }

    protected function resolveNamespace(): string
    {
        return Layer::infrastructure->resolveNamespace(suffix: '\\Laravel\\Providers');
    }

    protected function resolveRefactor(): void
    {
        if (! $this->option('skip-refactor')) {
            $filesystem = new Filesystem;
            $filesystem->ensureDirectoryExists($this->resolveInfrastructurePath());
            $filesystem->moveDirectory(from: app_path(path: 'Providers'), to: $this->resolveInfrastructurePath());
            $configPath = base_path(path: '/config/app.php');
            $contents = Str::replace(
                search: 'App\\Providers',
                replace: $this->resolveNamespace(),
                subject: $filesystem->get($configPath)
            );

            $filesystem->put(path: $configPath, contents: $contents);

            foreach ($filesystem->files($this->resolveInfrastructurePath()) as $file) {
                $contents = Str::replace(
                    search: 'App\\Providers',
                    replace: $this->resolveNamespace(),
                    subject: $filesystem->get($file)
                );

                $filesystem->put(path: $file, contents: $contents);
            }
        }
    }
}
