<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithPath;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;

class MakeModelCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

    protected $signature = 'diamond:model {name} {domain} {--f|factory} {--m|migration} {--force}';

    protected $description = 'create new model';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating model file to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            identifier: 'Models',
            domain: 'Shared\\' . $this->resolveDomainArgument(),
        );

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $factoryContractClassName = $this->resolveClassNameByFile(name: $fileName) . 'Factory';

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveClassNameByFile(name: $fileName),
            factoryContract: $factoryContractClassName,
            factoryContractNamespace: $this->resolveNamespace(
                identifier: 'Contracts\\Database\\Factories\\',
                domain: 'Shared',
            )
        );

        $filePresent = FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    destinationPath: $destinationPath,
                ),
                withForce: $this->resolveForceOption()
            );

        if ($filePresent && ! $this->resolveForceOption()) {
            $this->warn(string: $fileName . ' already exists.');

            return;
        }

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolveFactoryOption()
                        ? $this->resolvePathForStub(name: 'model-factory')
                        : $this->resolvePathForStub(name: 'model'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                )
            );

        $this->resolveMigration(fileName: $fileName);

        $this->resolveFactory(fileName: $fileName);

        $this->info(string: 'Successfully generate model file');
    }

    protected function resolveFactory(string $fileName): void
    {
        if ($this->resolveFactoryOption()) {
            Artisan::call(
                command: 'diamond:factory',
                parameters: [
                    'name' => $this->resolveClassNameByFile(name: $fileName) . 'Factory',
                    'domain' => $this->resolveDomainArgument(),
                ]
            );
        }
    }

    protected function resolveMigration(string $fileName): void
    {
        if ($this->option(key: 'migration')) {
            $tableName = $this->resolveClassNameByFile(name: $fileName);

            Artisan::call(command: 'diamond:migration Create' . Str::pluralStudly($tableName) . 'Table --create=' . $tableName);
        }
    }
}
