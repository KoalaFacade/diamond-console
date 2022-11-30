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
         * @var string $infrastructurePath
         */
        $infrastructurePath = config(key: 'diamond.structures.infrastructure');

        $namespace = "$infrastructurePath\\$domain\\Mail";

        $destinationPath = base_path(path: "src/$infrastructurePath/$domain/Model");

        $stubPath = __DIR__ . '/../../stubs/model.stub';

        /**
         * @var  array<string>  $placeholders
         */
        $placeholders = [
            'namespace' => $namespace,
            'class' => $name,
        ];

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->deleteDirectory($destinationPath);
        }

        $fileName = $name . '.php';

        $existsFile = $filesystem->exists(path: $destinationPath . '/' . $fileName);

        if (($this->option('migration') && ! $existsFile) || ($this->option('migration') && $this->option('force'))) {
            Artisan::call(command: "diamond:migration $name");
        }

        if (! $existsFile) {
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
