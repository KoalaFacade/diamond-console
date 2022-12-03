<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasBaseArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithPath;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;

class MakeModelCommand extends Command
{
    use InteractsWithPath, HasBaseArguments;

    protected $signature = 'diamond:model {name} {domain} {--f|factory} {--m|migration} {--force}';

    protected $description = 'create new model';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating model files to your project');

        $name = $this->resolveArgumentForName() . '.php';

        $namespace = Str::of(string: 'Models')
            ->start(prefix: $this->resolveArgumentForDomain() . '\\')
            ->start(prefix: 'Shared\\')
            ->start(prefix: $this->resolvePathForDomain() . '\\');

        $destinationPath = $this->resolveDestinationByNamespace(namespace: $namespace);

        $placeholders = [
            'namespace' => $namespace->toString(),
            'class' => $this->resolveClassNameByFile(name: $name),
        ];

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->delete(paths: $destinationPath . '/' . $name);
        }

        $isFileExists = $filesystem->exists(path: $destinationPath . '/' . $name);

        if ($isFileExists) {
            $this->warn(string: $name . ' already exists.');

            return;
        }

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'model'),
                    destinationPath: $destinationPath,
                    fileName: $name,
                    placeholders: $placeholders,
                )
            );

        $this->resolveForMigration(name: $name);

        $this->resolveForFactory(name: $name);

        $this->info(string: 'Successfully generate model file');
    }

    protected function resolveForFactory(string $name): void
    {
        if ($this->option(key: 'factory')) {
            Artisan::call(
                command: 'diamond:factory',
                parameters: [
                    'name' => $this->resolveClassNameByFile(name: $name) . 'Factory',
                    'domain' => $this->resolveArgumentForDomain(),
                ]
            );
        }
    }

    protected function resolveForMigration(string $name): void
    {
        if ($this->option(key: 'migration') && $this->option('force')) {
            Artisan::call(command: 'diamond:migration ' . $this->resolveClassNameByFile(name: $name));
        }
    }
}
