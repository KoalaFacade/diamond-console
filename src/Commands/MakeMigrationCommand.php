<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\StubResolver\CopyStubAction;

class MakeMigrationCommand extends Command
{
    protected $signature = 'diamond:migration {name} {--force}';

    protected $description = 'create new migration';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating migration files to your project');

        /**
         * @var  string  $name
         */
        $name = $this->argument(key: 'name');

        $destinationPath = base_path(path: 'database/migrations');

        $stubPath = __DIR__ . '/../../stubs/migration.stub';

        $tableName = Str::snake(Str::pluralStudly($name));

        $fileName = now()->format('Y_m_d_his') . '_create_' . $tableName . '_table.php';

        /**
         * @var  array<string>  $placeholders
         */
        $placeholders = [
            'tableName' => $tableName,
        ];

        CopyStubAction::resolve()
            ->execute(
                stubPath: $stubPath,
                destinationPath: $destinationPath,
                fileName: $fileName,
                placeholders: $placeholders
            );

        $this->info(string: 'Successfully generate migration file');
    }
}
