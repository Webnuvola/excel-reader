<?php

namespace Webnuvola\ExcelReader\File;

class TemporaryFile extends File
{
    /**
     * File destructor.
     */
    public function __destruct()
    {
        unlink($this->path);
    }
}
