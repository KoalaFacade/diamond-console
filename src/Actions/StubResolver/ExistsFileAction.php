<?php

namespace KoalaFacade\DiamondConsole\Actions\StubResolver;

use Illuminate\Filesystem\Filesystem;
use KoalaFacade\DiamondConsole\Foundation\Action;

class ExistsFileAction extends Action
{
    /**
     * Check exists file
     *
     * @param  string  $path
     * @param  string  $name
     * @return bool
     */
    public function execute($path, $name): bool
    {
        $filesystem = new Filesystem;

        return $filesystem->exists($path . '/' . $name . '.php');
    }
}
