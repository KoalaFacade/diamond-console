<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;

class MakeModelCommand extends Command
{
    protected $signature = 'diamond:model {name} {domain} {--m|migration} {--force}';

    protected $description = 'create new model';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating model files to your project');

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

        $namespace = "$domainPath\\Shared\\$domain\\Models";

        $destinationPath = base_path(path: "$basePath/$domainPath/Shared/$domain/Models");

        $stubPath = __DIR__ . '/../../stubs/model.stub';

        /**
         * @var  array<string>  $placeholders
         */
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

        if (($this->option('migration') && ! $isFileExists) || ($this->option('migration') && $this->option('force'))) {
            Artisan::call(command: "diamond:migration $name");
        }

        if (! $isFileExists) {
            CopyStubAction::resolve()
                ->execute(
                    stubPath: $stubPath,
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                );
        } else {
            $this->error(string: $fileName . ' already exists.');
        }

        $this->info(string: 'Successfully generate model file');
    }
}
