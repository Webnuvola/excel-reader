<?php

namespace Webnuvola\ExcelReader;

use Box\Spout\Reader\Common\Creator\ReaderFactory as BoxSpout3Reader;
use Webnuvola\ExcelReader\Exceptions\LibraryNotFoundException;
use Webnuvola\ExcelReader\Libraries\BoxSpout3Library;
use Webnuvola\ExcelReader\Libraries\LibraryInterface;

class ExcelReaderManager
{
    /**
     * Resolve library interface.
     *
     * @return \Webnuvola\ExcelReader\Libraries\LibraryInterface
     *
     * @throws \Webnuvola\ExcelReader\Exceptions\LibraryNotFoundException
     */
    public static function resolve(): LibraryInterface
    {
        if (class_exists(BoxSpout3Reader::class)) {
            return new BoxSpout3Library();
        }

        throw new LibraryNotFoundException('Libray box/spout not found');
    }
}
