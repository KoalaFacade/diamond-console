<?php

namespace KoalaFacade\DiamondConsole\Actions\Stub;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\DataTransferObjects\PlaceholderData;
use KoalaFacade\DiamondConsole\Foundation\Action;

/**
 * @template TKey of array-key
 * @template TValue
 */
class ReplacePlaceholderAction extends Action
{
    /**
     * Replace Placeholder Stub data
     *
     * @param  string  $filePath
     * @param  PlaceholderData  $placeholders
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function execute(string $filePath, PlaceholderData $placeholders): void
    {
        $filesystem = new Filesystem;

        $stub = Str::of(string: $filesystem->get(path: $filePath));

        /**
         * @var string $placeholder
         * @var string $replacement
         */
        foreach ($placeholders->toArray() as $placeholder => $replacement) {
            if (filled($replacement)) {
                $stub = $stub
                    ->replace(
                        search: "{{ $placeholder }}",
                        replace: $replacement
                    );
            }
        }

        $contents = $stub;

        $filesystem->ensureDirectoryExists(path: Str::of($filePath)->beforeLast(search: '/'));

        $filesystem->put(path: $filePath, contents: $contents);
    }
}
