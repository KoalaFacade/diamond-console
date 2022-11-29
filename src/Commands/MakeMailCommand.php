<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;
use KoalaFacade\DiamondConsole\Actions\StubResolver\ExistsFileAction;

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
         * @var  string  $namespace
         */
        $namespace = "Infrastructure\\{$domain}\\Mail";

        /**
         * @var  string  $destinationPath
         */
        $destinationPath = base_path("src/Infrastructure/{$domain}/Mail");

        /**
         * @var  string  $stubPath
         */
        $stubPath = __DIR__ . '/../../stubs/mail.stub';

        /**
         * @var  array<string>  $replacements
         */
        $replacements = [
            'namespace' => $namespace,
            'class' => $name,
            'subject' => Str::ucfirst($name),
        ];

        if ($this->option('force')) {
            $filesystem = new Filesystem;
            $filesystem->deleteDirectory($destinationPath);
        }

        $existsFile = ExistsFileAction::resolve()->execute($destinationPath, $name);

        if (! $existsFile) {
            CopyStubAction::resolve()->execute($stubPath, $destinationPath, $name, $replacements);
        } else {
            $this->error(string: $name . ' already exists.');
        }

        $this->info(string: 'Successfully generate base file');
    }
}
