<?php

namespace KoalaFacade\DiamondConsole\Actions\StubResolver;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\Action;

class ReplacePlaceholderAction extends Action
{
    /**
     * Replace Placeholder Stub data
     *
     * @param  string  $filePath
     * @param  array<string>  $replacements
     * @return void
     */
    public function execute($filePath, $replacements): void
    {
        $filesystem = new Filesystem;
        $stub = Str::of($filesystem->get($filePath));

        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        $contents = $stub;

        $filesystem->ensureDirectoryExists(
            Str::of($filePath)
                ->beforeLast('/'),
        );

        $filesystem->put($filePath, $contents);
    }
}
