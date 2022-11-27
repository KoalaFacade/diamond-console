<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\Actions\Composer\ResolveComposerAutoLoaderAction;
use Throwable;

class GenerateBaseStructureCommand extends Command
{
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
                $this->line(string: 'Skipping generating ' . $structure . ' , the base directory is exists');

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
        /** @var string $path */
        $path = config(key: 'diamond.base_directory');

        return base_path(path: $path);
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
