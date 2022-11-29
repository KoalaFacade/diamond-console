<?php

namespace KoalaFacade\DiamondConsole\Actions\StubResolver;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\Action;

class ReplacePlaceholderAction extends Action
{
    /**
     * Replace Placeholder Stub data
     *
     * @param  string  $filePath
     * @param  array<string>  $placeholders
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function execute(string $filePath, array $placeholders): void
    {
        $filesystem = new Filesystem();
        $stub = Str::of(string: $filesystem->get(path: $filePath));

        foreach ($placeholders as $placeholder => $replacement) {
            $stub = $stub->replace(search: "{{ $placeholder }}", replace: $replacement);
        }

        $contents = $stub;

        $filesystem->ensureDirectoryExists(path: Str::of($filePath)->beforeLast(search: '/'),);

        $filesystem->put(path: $filePath, contents: $contents);
    }
}
