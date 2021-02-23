<?php

namespace Webnuvola\ExcelReader\File;

class File implements FileInterface
{
    /**
     * File path.
     *
     * @var string
     */
    protected string $path;

    /**
     * File constructor.
     *
     * @param  string $path
     */
    public function __construct(string $path)
    {
        $this->path = realpath($path);
    }

    /**
     * Return file path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
