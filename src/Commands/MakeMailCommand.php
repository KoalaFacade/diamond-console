<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;

class MakeMailCommand extends Command
{
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

        /**
         * @var  string  $domain
         */
        $domain = $this->argument(key: 'domain');

        /**
         * @var string $basePath
         */
        $basePath = config(key: 'diamond.base_directory');

        /**
         * @var string $infrastructurePath
         */
        $infrastructurePath = config(key: 'diamond.structures.infrastructure');

        $namespace = "$infrastructurePath\\$domain\\Mail";

        $destinationPath = base_path(path: "$basePath/$infrastructurePath/$domain/Mail");

        $stubPath = __DIR__ . '/../../stubs/mail.stub';

        /**
         * @var  array<string>  $placeholders
         */
        $placeholders = [
            'namespace' => $namespace,
            'class' => $name,
            'subject' => Str::ucfirst($name),
        ];

        $filesystem = new Filesystem();

        if ($this->option(key: 'force')) {
            $filesystem->deleteDirectory($destinationPath);
        }

        $fileName = $name . '.php';

        $isFileExists = $filesystem->exists(path: $destinationPath . '/' . $fileName);

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

        $this->info(string: 'Successfully generate base file');
    }
}
