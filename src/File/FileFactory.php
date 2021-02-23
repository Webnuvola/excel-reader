<?php

namespace Webnuvola\ExcelReader\File;

use Webnuvola\ExcelReader\Exceptions\TemporaryFileException;

class FileFactory
{
    /**
     * Create File instance from path.
     *
     * @param  string $path
     * @return \Webnuvola\ExcelReader\File\FileInterface
     */
    public static function createFromPath(string $path): FileInterface
    {
        return new File($path);
    }

    /**
     * Create File instance from content and extension.
     *
     * @param  string $content
     * @param  string $extension
     * @return \Webnuvola\ExcelReader\File\FileInterface
     */
    public static function createFromString(string $content, string $extension): FileInterface
    {
        $path = null;

        do {
            if ($path) {
                unlink($path);
            }

            $path = tempnam(sys_get_temp_dir(), 'excel-reader-');

            if ($path === false) {
                throw new TemporaryFileException('Can\'t create temporary file.');
            }
        } while (file_exists("{$path}.{$extension}"));

        unlink($path);
        $path = "{$path}.{$extension}";

        $fp = fopen($path, 'wb');
        fwrite($fp, $content);
        fclose($fp);

        return new TemporaryFile($path);
    }
}
