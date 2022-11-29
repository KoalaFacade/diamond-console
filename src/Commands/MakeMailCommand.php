<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;

class MakeMailCommand extends Command
{
    protected $signature = 'diamond:mail {name} {domain} {--force}';

    protected $description = 'create new mail';

    public function handle(): void
    {
        $this->info(string: 'Generating files to our project');

        /**
         * @var  string  $name
         */
        $name = $this->argument('name');

        /**
         * @var  string  $domain
         */
        $domain = $this->argument('domain');

        /**
         * @var string $infrastructurePath
         */
        $infrastructurePath = config('diamond.structures.infrastructure');

        /**
         * @var  string  $namespace
         */
        $namespace = "{$infrastructurePath}\\{$domain}\\Mail";

        /**
         * @var  string  $destinationPath
         */
        $destinationPath = base_path("src/{$infrastructurePath}/{$domain}/Mail");

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

        $filesystem = new Filesystem;

        if ($this->option('force')) {
            $filesystem->deleteDirectory($destinationPath);
        }

        $fileName = $name . '.php';

        $existsFile = $filesystem->exists($destinationPath . '/' . $fileName);

        if (! $existsFile) {
            CopyStubAction::resolve()->execute($stubPath, $destinationPath, $fileName, $placeholders);
        } else {
            $this->error(string: $fileName . ' already exists.');
        }

        $this->info(string: 'Successfully generate base file');
    }
}
