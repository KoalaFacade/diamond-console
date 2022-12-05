<?php

namespace KoalaFacade\DiamondConsole\Actions\Stub;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
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

        $resolved = $placeholders
            ->resolveArrayKeyUsing(fn (string $key) => Str::camel($key))
            ->toArray();

        $stub = $filesystem->get(path: $filePath);

        Collection::make($resolved)
            ->filter()
            ->each(
                function ($value, $key) use (&$stub) {
                    /**
                     * @var string $replacement
                     * @var string $placeholder
                     */
                    [$replacement, $placeholder] = [$value, $key];

                    $stub = Str::replace(
                        search: "{{ $placeholder }}",
                        replace: $replacement,
                        subject: $stub
                    );
                }
            );

        $contents = $stub;

        $filesystem->ensureDirectoryExists(path: Str::of($filePath)->beforeLast(search: '/'));

        $filesystem->put(path: $filePath, contents: $contents);
    }
}
