<?php

namespace Webnuvola\ExcelReader\File;

interface FileInterface
{
    /**
     * Return file path.
     *
     * @return string
     */
    public function getPath(): string;
}
