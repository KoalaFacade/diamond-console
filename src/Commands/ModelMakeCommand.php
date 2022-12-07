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
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;
use KoalaFacade\DiamondConsole\Support\Source;

class ModelMakeCommand extends Command
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'domain:make:model {name} {domain} {--f|factory} {--m|migration} {--force}';

    protected $description = 'Create a new model';

    /**
     * @throws FileNotFoundException | FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating model file to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = Source::resolveNamespace(
            structures: Source::resolveDomainPath(),
            prefix: 'Shared\\' . $this->resolveDomainArgument(),
            suffix: 'Models',
        );

        $destinationPath = Source::resolveNamespacePath(namespace: $namespace);

        $factoryContractClassName = Source::resolveNameFromPhp(name: $fileName) . 'Factory';

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: Source::resolveNameFromPhp(name: $fileName),
            factoryContract: $factoryContractClassName,
            factoryContractNamespace: Source::resolveNamespace(
                structures: Source::resolveDomainPath(),
                prefix: 'Shared',
                suffix: 'Contracts\\Database\\Factories',
            )
        );

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    namespacePath: $destinationPath,
                ),
                withForce: $this->resolveForceOption()
            );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolveFactoryOption()
                        ? Source::resolveStubForPath(name: 'model-factory')
                        : Source::resolveStubForPath(name: 'model'),
                    targetPath: $destinationPath,
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
                    'name' => Str::finish(Source::resolveNameFromPhp(name: $fileName), cap: 'Factory'),
                    'domain' => $this->resolveDomainArgument(),
                ]
            );
        }
    }

    protected function resolveMigration(string $fileName): void
    {
        if ($this->option(key: 'migration')) {
            $tableName = Source::resolveNameFromPhp(name: $fileName);

            Artisan::call(command: 'application:migration Create' . Str::pluralStudly($tableName) . 'Table --create=' . $tableName);
        }
    }
}
