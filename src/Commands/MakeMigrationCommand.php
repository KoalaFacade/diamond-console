<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;

class MakeMigrationCommand extends Command
{
    use HasArguments;

    protected $signature = 'diamond:migration {name} {--force}';

    protected $description = 'create new migration';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info(string: 'Generating migration files to your project');

        $destinationPath = base_path(path: 'database/migrations');

        $stubPath = __DIR__ . '/../../stubs/migration.stub';

        $tableName = Str::snake(Str::pluralStudly($this->resolveNameArgument()));

        $fileName = Carbon::now()->format(format: 'Y_m_d_his') . '_create_' . $tableName . '_table.php';

        $placeholders = new PlaceholderData(tableName: $tableName);

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $stubPath,
                    destinationPath: $destinationPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Successfully generate migration file');
    }
}
