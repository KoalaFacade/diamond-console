<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasBaseArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithPath;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;

class MakeFactoryCommand extends Command
{
    use InteractsWithPath, HasBaseArguments;

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
        $name = 'Abstract' . $this->resolveArgumentForName() . '.php';

        $namespace = Str::of(string: 'Contracts')
            ->start(prefix: 'Models\\')
            ->start(prefix: $this->resolveArgumentForDomain() . '\\')
            ->start(prefix: 'Shared\\')
            ->start(prefix: $this->resolvePathForDomain() . '\\');

        $destination = $this->resolveDestinationByNamespace(namespace: $namespace);

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->delete(paths: $destination . '/' . $name);
        }

        if ($filesystem->exists(path:  $destination . '/' . $name)) {
            $this->warn(string: $name . ' is already exists at ' . $destination);

            return;
        }

        $placeholders = [
            'namespace' => $namespace->toString(),
            'class' => $this->resolveClassNameByFile(name: $name),
        ];

        CopyStubAction::resolve()
            ->execute(
                new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'factory-interface'),
                    destinationPath: $destination,
                    fileName: $name,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Succeed generate Factory Interface at ' . $destination . '/' . $name);
    }

    /**
     * @throws FileNotFoundException
     */
    protected function resolveGenerateForFactoryConcrete(): void
    {
        $name = $this->resolveArgumentForName() . '.php';

        $namespace = Str::of(string: 'Factories')
            ->start(prefix: 'Database\\')
            ->start(prefix: $this->resolveArgumentForDomain() . '\\')
            ->start(prefix: $this->resolvePathForInfrastructure() . '\\');

        $destination = $this->resolveDestinationByNamespace(namespace: $namespace);

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->delete(paths: $destination . '/' . $name);
        }

        if ($filesystem->exists(path:  $destination . '/' . $name)) {
            $this->warn(string: $name . ' is already exists at ' . $destination);

            return;
        }

        $placeholders = [
            'namespace' => $namespace->toString(),
            'class' => $this->resolveClassNameByFile(name: $name),
        ];

        CopyStubAction::resolve()
            ->execute(
                new CopyStubData(
                    stubPath: $this->resolvePathForStub(name: 'factory'),
                    destinationPath: $destination,
                    fileName: $name,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Succeed generate Factory concrete at ' . $destination . '/' . $name);
    }
}
