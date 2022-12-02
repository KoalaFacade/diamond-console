<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\concerns\HasBaseArguments;
use KoalaFacade\DiamondConsole\Commands\concerns\InteractsWithPath;

class MakeMailCommand extends Command
{
    use InteractsWithPath, HasBaseArguments;

    protected $signature = 'diamond:mail {name} {domain} {--force}';

    protected $description = 'create new mail';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating files to our project');

        /**
         * @var  string  $name
         */
        $name = $this->argument(key: 'name');

        $namespace = Str::of(string: 'Mail')
            ->start(prefix: $this->resolveArgumentForDomain() . '\\')
            ->start(prefix: $this->resolvePathForInfrastructure() . '\\');

        $destinationPath = $this->resolveDestinationByNamespace(namespace: $namespace);

        /**
         * @var  array<string>  $placeholders
         */
        $placeholders = [
            'namespace' => $namespace->toString(),
            'class' => $name,
            'subject' => Str::ucfirst($name),
        ];

        $fileName = $name . '.php';

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->delete($destinationPath . '/' . $fileName);
        }

        $isFileExists = $filesystem->exists(path: $destinationPath . '/' . $fileName);

        if ($isFileExists) {
            $this->error(string: $fileName . ' already exists.');

            return;
        }

        CopyStubAction::resolve()
            ->execute(
                stubPath: $this->resolvePathForStub(name: 'mail'),
                destinationPath: $destinationPath,
                fileName: $fileName,
                placeholders: $placeholders
            );

        $this->info(string: 'Successfully generate base file');
    }
}
