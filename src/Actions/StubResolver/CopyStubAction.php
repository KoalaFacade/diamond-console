<?php

namespace KoalaFacade\DiamondConsole\Actions\StubResolver;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
     * @param  string  $fileName
     * @param  array<string>  $placeholders
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function execute(string $stubPath, string $destinationPath, string $fileName, array $placeholders): void
    {
        $filePath = $destinationPath . '/' . $fileName;
        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists(
            Str::of($destinationPath)
                ->beforeLast('/'),
        );
        $filesystem->makeDirectory($destinationPath);
        $filesystem->copy($stubPath, $filePath);

        ReplacePlaceholderAction::resolve()->execute($filePath, $placeholders);
    }
}
