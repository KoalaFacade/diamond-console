<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithPath;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;

class MakeFactoryCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

    protected $signature = 'diamond:factory {name} {domain} {--force}';

    protected $description = 'create new enum';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating factory & interface file to your project');

        $this->resolveGenerateForFactoryInterface();

        $this->resolveGenerateForFactoryConcrete();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function resolveGenerateForFactoryInterface(): void
    {
        $fileName = 'Abstract' . $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            identifier: 'Models\\Contracts',
            domain: 'Shared\\' . $this->resolveDomainArgument(),
        );

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $filePresent = FilePresentAction::resolve()->execute(
            data: new FilePresentData(
                fileName: $fileName,
                destinationPath: $destinationPath,
            ),
            withForce: $this->resolveForceOption()
        );

        if ($filePresent) {
            $this->warn(string: $fileName . ' is already exists at ' . $destinationPath);

            return;
        }

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveClassNameByFile(name: $fileName)
        );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'factory-interface'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                )
            );

        $this->info(string: 'Succeed generate Factory Interface at ' . $destinationPath . '/' . $fileName);
    }

    /**
     * @throws FileNotFoundException
     */
    protected function resolveGenerateForFactoryConcrete(): void
    {
        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = Str::of(string: 'Factories')
            ->start(prefix: 'Database\\')
            ->start(prefix: $this->resolveDomainArgument() . '\\')
            ->start(prefix: $this->resolvePathForInfrastructure() . '\\');

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $filePresent = FilePresentAction::resolve()->execute(
            data: new FilePresentData(
                fileName: $fileName,
                destinationPath: $destinationPath,
            ),
            withForce: $this->resolveForceOption()
        );

        if ($filePresent) {
            $this->warn(string: $fileName . ' is already exists at ' . $destinationPath);

            return;
        }

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveClassNameByFile(name: $fileName)
        );

        CopyStubAction::resolve()
            ->execute(
                new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'factory'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Succeed generate Factory concrete at ' . $destinationPath . '/' . $fileName);
    }
}
