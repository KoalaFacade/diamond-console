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
         * @var string $infrastructurePath
         */
        $infrastructurePath = config(key: 'diamond.structures.infrastructure');

        /**
         * @var  string  $namespace
         */
        $namespace = "$infrastructurePath\\$domain\\Mail";

        /**
         * @var  string  $destinationPath
         */
        $destinationPath = base_path(path: "src/$infrastructurePath/$domain/Mail");

        /**
         * @var  string  $stubPath
         */
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

        $existsFile = $filesystem->exists(path: $destinationPath . '/' . $fileName);

        if (! $existsFile) {
            CopyStubAction::resolve()->execute($stubPath, $destinationPath, $fileName, $placeholders);
        } else {
            $this->error(string: $fileName . ' already exists.');
        }

        $this->info(string: 'Successfully generate base file');
    }
}
