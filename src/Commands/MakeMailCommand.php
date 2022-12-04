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
use KoalaFacade\DiamondConsole\Exceptions\FileAlreadyExistException;

class MakeMailCommand extends Command
{
    use InteractsWithPath, HasArguments, HasOptions;

    protected $signature = 'diamond:mail {name} {domain} {--force}';

    protected $description = 'create new mail';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating file to our project');

        $namespace = Str::of(string: 'Mail')
            ->start(prefix: $this->resolveDomainArgument() . '\\')
            ->start(prefix: $this->resolvePathInfrastructure() . '\\');

        $destinationPath = $this->resolveNamespaceTarget(namespace: $namespace);

        $placeholders = new PlaceholderData(
            namespace: $namespace->toString(),
            class: $this->resolveNameArgument(),
            subject: Str::ucfirst($this->resolveNameArgument()),
        );

        $fileName = $this->resolveNameArgument() . '.php';

        FilePresentAction::resolve()
            ->execute(
                data: new FilePresentData(
                    fileName: $fileName,
                    destinationPath: $destinationPath,
                ),
                withForce: $this->resolveForceOption(),
            );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'mail'),
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                ),
            );

        $this->info(string: 'Successfully generate base file');
    }
}
