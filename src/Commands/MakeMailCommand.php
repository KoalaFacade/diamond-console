<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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

class MakeMailCommand extends Command
{
    use InteractsWithDDD, HasArguments, HasOptions;

    protected $signature = 'infrastructure:make:mail {name} {domain} {--force}';

    protected $description = 'Create a new mail';

    /**
     * @throws FileNotFoundException
     * @throws FileAlreadyExistException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating file to our project');

        $namespace = Str::of(string: 'Mail')
            ->start(prefix: $this->resolveDomainArgument() . '\\')
            ->start(prefix: $this->resolveInfrastructurePath() . '\\');

        $destinationPath = $this->resolveNamespacePath(namespace: $namespace);

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
                    namespacePath: $destinationPath,
                ),
                withForce: $this->resolveForceOption(),
            );

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $this->resolveStubForPath(name: 'mail'),
                    namespacePath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders,
                ),
            );

        $this->info(string: 'Successfully generate base file');
    }
}
