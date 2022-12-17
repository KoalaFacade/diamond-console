<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Actions\Stub\CopyStubAction;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\DataTransferObjects\CopyStubData;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class MigrationMakeCommand extends Command
{
    use HasArguments, HasOptions;

    protected $signature = 'diamond:migration {name} {--create=} {--table=} {--force}';

    protected $description = 'Create a new migration';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $targetPath = base_path(path: 'database/migrations');

        $stub = 'migration';

        if ($this->option('create')) {
            $stub .= '-create';
        } elseif ($this->option('table')) {
            $stub .= '-table';
        }

        $stubPath = Source::resolveStubForPath(name: $stub);

        $migrationName = Str::snake($this->resolveNameArgument());

        $fileName = Carbon::now()->format(format: 'Y_m_d_his') . '_' . $migrationName . '.php';

        $tableName = $this->resolveTableName() ? Str::snake(Str::pluralStudly($this->resolveTableName())) : '';

        $placeholders = new PlaceholderData(tableName: $tableName);

        CopyStubAction::resolve()
            ->execute(
                data: new CopyStubData(
                    stubPath: $stubPath,
                    targetPath: $targetPath,
                    fileName: $fileName,
                    placeholders: $placeholders
                )
            );

        $this->info(string: 'Successfully generate migration file');
    }
}
