<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\concerns\HasBaseArguments;
use KoalaFacade\DiamondConsole\Commands\concerns\InteractsWithPath;

class MakeEnumCommand extends Command
{
    use InteractsWithPath, HasBaseArguments;

    protected $signature = 'diamond:enum {name} {domain} {--force}';

    protected $description = 'create new enum';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating enum files to your project');

        $name = $name = $this->resolveArgumentForName() . '.php';

        $namespace = Str::of(string: 'Enums')
            ->start(prefix: $this->resolveArgumentForDomain() . '\\')
            ->start(prefix: $this->resolvePathForDomain() . '\\');

        $destinationPath = $this->resolveDestinationByNamespace(namespace: $namespace);

        $placeholders = [
            'namespace' => $namespace->toString(),
            'class' => $this->resolveClassNameByFile(name: $name),
        ];

        if (version_compare(PHP_VERSION, '8.1.0', '<=')) {
            $this->error('The required PHP version is 8.1 while the version you have is ' . PHP_VERSION);

            return;
        }

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->delete($destinationPath . '/' . $name);
        }

        $isFileExists = $filesystem->exists(path: $destinationPath . '/' . $name);

        if ($isFileExists) {
            $this->warn(string: $name . ' already exists.');

            return;
        }

        CopyStubAction::resolve()
            ->execute(
                stubPath: $this->resolvePathForStub(name: 'enum'),
                destinationPath: $destinationPath,
                fileName: $name,
                placeholders: $placeholders
            );

        $this->info(string: 'Successfully generate enum file');
    }
}
