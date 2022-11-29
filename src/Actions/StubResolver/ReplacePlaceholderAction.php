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
     * @param  array<string>  $placeholders
     * @return void
     */
    public function execute($filePath, $placeholders): void
    {
        $filesystem = new Filesystem;
        $stub = Str::of($filesystem->get($filePath));

        foreach ($placeholders as $placeholder => $replacement) {
            $stub = $stub->replace("{{ {$placeholder} }}", $replacement);
        }

        $contents = $stub;

        $filesystem->ensureDirectoryExists(
            Str::of($filePath)
                ->beforeLast('/'),
        );

        $filesystem->put($filePath, $contents);
    }
}
