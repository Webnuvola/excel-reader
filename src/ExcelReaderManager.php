<?php

namespace Webnuvola\ExcelReader;

use OpenSpout\Reader\Common\Creator\ReaderFactory as OpenSpout3ReaderFactory;
use Webnuvola\ExcelReader\Exceptions\LibraryNotFoundException;
use Webnuvola\ExcelReader\Libraries\OpenSpout3Library;
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
        if (class_exists(OpenSpout3ReaderFactory::class)) {
            return new OpenSpout3Library();
        }

        throw new LibraryNotFoundException('Libray openspout/openspout not found');
    }
}
