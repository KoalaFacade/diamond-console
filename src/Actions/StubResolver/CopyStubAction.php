<?php

namespace KoalaFacade\DiamondConsole\Actions\StubResolver;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\Action;

class CopyStubAction extends Action
{
    /**
     * Copy Stub to Diamond Infrastructure
     *
     * @param  string  $stubPath
     * @param  string  $destinationPath
     * @param  string  $name
     * @param  array<string>  $replacements
     * @return void
     */
    public function execute($stubPath, $destinationPath, $name, $replacements): void
    {
        $filesystem = new Filesystem;
        $filePath = $destinationPath . '/' . $name . '.php';
        $filesystem->ensureDirectoryExists(
            Str::of($destinationPath)
                ->beforeLast('/'),
        );
        $filesystem->makeDirectory($destinationPath);
        $filesystem->copy($stubPath, $filePath);

        ReplacePlaceholderAction::resolve()->execute($filePath, $replacements);
    }
}
