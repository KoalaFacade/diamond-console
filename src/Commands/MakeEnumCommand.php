<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;

class MakeEnumCommand extends Command
{
    protected $signature = 'diamond:enum {name} {domain} {--force}';

    protected $description = 'create new enum';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating enum files to your project');

        /**
         * @var  string  $name
         */
        $name = $this->argument(key: 'name');

        /**
         * @var  string  $domain
         */
        $domain = $this->argument(key: 'domain');

        /**
         * @var string $basePath
         */
        $basePath = config(key: 'diamond.base_directory');

        /**
         * @var string $domainPath
         */
        $domainPath = config(key: 'diamond.structures.domain');

        $namespace = "$domainPath\\$domain\\Enums";

        $destinationPath = base_path(path: "$basePath/$domainPath/$domain/Enums");

        $stubPath = __DIR__ . '/../../stubs/enum.stub';

        $placeholders = [
            'namespace' => $namespace,
            'class' => $name,
        ];

        if (version_compare(PHP_VERSION, '8.1.0', '<=')) {
            $this->error('The required PHP version is 8.1 while the version you have is ' . PHP_VERSION);

            return;
        }

        $fileName = $name . '.php';

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->delete($destinationPath . '/' . $fileName);
        }

        $isFileExists = $filesystem->exists(path: $destinationPath . '/' . $fileName);

        if (! $isFileExists) {
            CopyStubAction::resolve()
                ->execute(
                    stubPath: $stubPath,
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                );

            $this->info(string: 'Successfully generate enum file');

            return;
        }

        $this->error(string: $fileName . ' already exists.');
    }
}
