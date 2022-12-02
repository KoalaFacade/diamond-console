<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\Actions\Composer\ResolveComposerAutoLoaderAction;
use KoalaFacade\DiamondConsole\Commands\concerns\InteractsWithPath;
use Throwable;

class InstallCommand extends Command
{
    use InteractsWithPath;

    protected $signature = 'diamond:install';

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

        $this->info(string: 'Successfully generate base file');
    }

    /**
     * @return string
     */
    protected function resolveBaseDirectoryPath(): string
    {
        return base_path(path: $this->resolveBasePath());
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
}
