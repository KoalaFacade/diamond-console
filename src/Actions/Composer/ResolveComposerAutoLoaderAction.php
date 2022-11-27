<?php

namespace KoalaFacade\DiamondConsole\Actions\Composer;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use KoalaFacade\DiamondConsole\Foundation\Action;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use stdClass;
use Throwable;

class ResolveComposerAutoLoaderAction extends Action
{
    public function __construct(protected Filesystem $fileSystem)
    {
        //
    }

    protected const BASE_STRUCTURES = [
        'Infrastructure',
        'Domain',
    ];

    /**
     * @throws FileNotFoundException
     * @throws UnableToReadFile
     * @throws UnableToWriteFile
     * @throws Throwable
     */
    public function execute(): bool
    {
        $composer = $this->fetchComposerContents();

        foreach (self::BASE_STRUCTURES as $structure) {
            $namespace = Str::of(string: $structure)->finish(cap: '\\');
            $directory = $namespace->replace(search: '\\', replace: '/');

            if (Arr::exists(array: $composer->autoload['psr-4'], key: $namespace->toString())) {
                continue;
            }

            Arr::set(
                array: $composer->autoload['psr-4'],
                key: $namespace->toString(),
                value: $directory->toString()
            );
        }

        return $this->updateComposerContents(contents: $composer);
    }

    /**
     * @return stdClass
     *
     * @throws FileNotFoundException
     * @throws Throwable
     */
    protected function fetchComposerContents(): stdClass
    {
        $path = $this->resolveBasePathForComposer();

        throw_unless(
            condition: $this->fileSystem->exists($path),
            exception: new FileNotFoundException(message: "Composer doesn't exists")
        );

        throw_unless(
            condition: $this->fileSystem->isReadable($path),
            exception: UnableToReadFile::fromLocation(location: $path)
        );

        throw_unless(
            condition: $this->fileSystem->isWritable($path),
            exception: UnableToWriteFile::atLocation(location: $path)
        );

        return (object) json_decode(
            json: $this->fileSystem->get(path: $path),
            associative: true
        );
    }

    protected function updateComposerContents(object $contents): bool
    {
        $path = $this->resolveBasePathForComposer();

        /** @var string $resolvedContents */
        $resolvedContents = json_encode(value: $contents, flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return (bool) $this->fileSystem->put(
            path: $path,
            contents: $resolvedContents
        );
    }

    protected function resolveBasePathForComposer(): string
    {
        return base_path(path: 'composer.json');
    }
}
