<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;

class MakeActionCommand extends Command
{
    protected $signature = 'diamond:action {name} {domain} {--force}';

    protected $description = 'create new action';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating action files to your project');

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

        $namespace = "$domainPath\\$domain\\Actions";

        $destinationPath = base_path(path: "$basePath/$domainPath/$domain/Actions");

        $stubPath = __DIR__ . '/../../stubs/action.stub';

        $placeholders = [
            'namespace' => $namespace,
            'class' => $name,
        ];

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

            $this->info(string: 'Successfully generate action file');

            return;
        }

        $this->error(string: $fileName . ' already exists.');
    }
}
