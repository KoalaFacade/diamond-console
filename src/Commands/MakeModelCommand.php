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
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithDDD;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class MakeModelCommand extends Command
{
    use InteractsWithDDD, HasArguments, HasOptions;

    protected $signature = 'domain:make:model {name} {domain} {--f|factory} {--m|migration} {--force}';

    protected $description = 'Create a new model';

    /**
     * @throws FileNotFoundException|FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating model file to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            structures: $this->resolveDomainPath(),
            suffix: 'Models',
            prefix: 'Shared\\' . $this->resolveDomainArgument(),
        );

        $destinationPath = $this->resolveNamespacePath(namespace: $namespace);

        $factoryContractClassName = $this->resolveNameFromPhp(name: $fileName) . 'Factory';

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveNameFromPhp(name: $fileName),
            factoryContract: $factoryContractClassName,
            factoryContractNamespace: $this->resolveNamespace(
                structures: $this->resolveDomainPath(),
                suffix: 'Contracts\\Database\\Factories',
                prefix: 'Shared',
            )
        );

        $filePresent = FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    namespacePath: $destinationPath,
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
                        ? $this->resolveStubForPath(name: 'model-factory')
                        : $this->resolveStubForPath(name: 'model'),
                    namespacePath: $destinationPath,
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
                command: 'infrastructure:make:factory',
                parameters: [
                    'name' => $this->resolveNameFromPhp(name: $fileName) . 'Factory',
                    'domain' => $this->resolveDomainArgument(),
                ]
            );
        }
    }

    protected function resolveMigration(string $fileName): void
    {
        if ($this->option(key: 'migration')) {
            $tableName = $this->resolveNameFromPhp(name: $fileName);

            Artisan::call(command: 'application:migration Create' . Str::pluralStudly($tableName) . 'Table --create=' . $tableName);
        }
    }
}
