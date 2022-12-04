<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Artisan;
use KoalaFacade\DiamondConsole\Actions\Filesystem\FilePresentAction;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithPath;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\Filesystem\FilePresentData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class MakeModelCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

    protected $signature = 'diamond:model {name} {domain} {--m|migration} {--force}';

    protected $description = 'create new model';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating model files to your project');

        $fileName = $this->resolveNameArgument() . '.php';

        $namespace = $this->resolveNamespace(
            identifier: 'Models',
            domain: 'Shared\\' . $this->resolveDomainArgument(),
        );

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $placeholders = new PlaceholderData(
            namespace: $namespace,
            class: $this->resolveClassNameByFile(name: $fileName),
        );

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    destinationPath: $destinationPath,
                ),
                withForce: $this->resolveForceOption()
            );

        if ($this->option('migration') || $this->resolveForceOption()) {
            Artisan::call(command: 'diamond:migration ' . $this->resolveClassNameByFile(name: $fileName));
        }

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'model'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                )
            );

        $this->info(string: 'Successfully generate model file');
    }
}
