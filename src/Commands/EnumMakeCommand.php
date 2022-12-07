<?php

namespace KoalaFacade\DiamondConsole\Commands;

use Illuminate\Console\Command;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasArguments;
use KoalaFacade\DiamondConsole\Commands\Concerns\HasOptions;
use KoalaFacade\DiamondConsole\Commands\Concerns\InteractsWithConsole;
use KoalaFacade\DiamondConsole\Contracts\Console;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Support\Source;

class EnumMakeCommand extends Command implements Console
{
    use HasArguments, HasOptions, InteractsWithConsole;

    protected $signature = 'domain:make:enum {name} {domain} {--force}';

    protected $description = 'Create a new enum';

    public function afterCreate(): void
    {
        $this->info(string: 'Successfully generate enum file');
    }

    public function beforeCreate(): void
    {
        $this->info(string: 'Generating enum file to your project');

        if (version_compare(version1: PHP_VERSION, version2: '8.1.0', operator: '<=')) {
            throw new \RuntimeException(
                message: 'The required PHP version is 8.1 while the version you have is ' . PHP_VERSION
            );
        }
    }

    public function getNamespace(): string
    {
        return Source::resolveNamespace(
            structures: Source::resolveDomainPath(),
            prefix: $this->resolveDomainArgument(),
            suffix: 'Enums'
        );
    }

    public function getStubPath(): string
    {
        return Source::resolveStubForPath(name: 'enum');
    }

    public function resolvePlaceholders(): PlaceholderData
    {
        return new PlaceholderData(
            namespace: $this->getNamespace(),
            class: $this->getClassName(),
        );
    }
}
