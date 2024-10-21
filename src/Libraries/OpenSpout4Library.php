<?php

namespace Webnuvola\ExcelReader\Libraries;

use Cocur\Slugify\Slugify;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Cell\FormulaCell;
use OpenSpout\Reader\AbstractReader;
use OpenSpout\Reader\CSV\Options as CSVOptions;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\ODS\Options as ODSOptions;
use OpenSpout\Reader\ODS\Reader as ODSReader;
use OpenSpout\Reader\XLSX\Options as XLSXOptions;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use Webnuvola\ExcelReader\Exceptions\UnsupportedTypeException;
use Webnuvola\ExcelReader\File\FileInterface;

class OpenSpout4Library extends Library implements LibraryInterface
{
    /**
     * Read the file and return data from selected sheet.
     *
     * @param  \Webnuvola\ExcelReader\File\FileInterface $file
     * @param  bool $hasHeaders
     * @param  int|string $sheetId
     * @return array
     *
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     * @throws \Webnuvola\ExcelReader\Exceptions\UnsupportedTypeException
     */
    public function read(FileInterface $file, bool $hasHeaders, int|string $sheetId): array
    {
        $reader = $this->createFromFile($file);
        $reader->open($file->getPath());

        $function = is_int($sheetId) ? 'getIndex' : 'getName';

        $headers = [];
        $headersCount = 0;
        $filler = [];
        $data = [];

        $first = $hasHeaders;
        $slugify = $hasHeaders ? new Slugify($this->slugifySettings) : null;
        $skipped = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->$function() !== $sheetId) {
                continue;
            }

            /** @var \OpenSpout\Common\Entity\Row $row */
            foreach ($sheet->getRowIterator() as $row) {
                if ($skipped < $this->skip) {
                    $skipped++;
                    continue;
                }

                if ($first) {
                    $headers = array_map(
                        fn (Cell $cell) => $slugify->slugify($this->getCellValue($cell)),
                        $row->getCells(),
                    );

                    $headersCount = count($headers);
                    $filler = array_fill(0, $headersCount, null);

                    $first = false;
                    continue;
                }

                $currentData = array_map(
                        fn (Cell $cell) => $this->getCellValue($cell),
                        $row->getCells(),
                    ) + $filler;

                if ($hasHeaders && count($currentData) > $headersCount) {
                    $currentData = array_slice($currentData, 0, $headersCount);
                }

                $data[] = $currentData;
            }

            break;
        }

        if (! $hasHeaders) {
            return $data;
        }

        return array_map(static fn ($values) => array_combine($headers, $values), $data);
    }

    /**
     * Return library version.
     *
     * @return int
     */
    public function version(): int
    {
        return 4;
    }

    /**
     * Return reader for current file.
     *
     * @param  \Webnuvola\ExcelReader\File\FileInterface $file
     * @return \OpenSpout\Reader\AbstractReader
     *
     * @throws \Webnuvola\ExcelReader\Exceptions\UnsupportedTypeException
     */
    protected function createFromFile(FileInterface $file): AbstractReader
    {
        $optionClass = null;
        $readerClass = null;

        $extension = $file->getExtension();

        if ($extension === 'csv') {
            $optionClass = CSVOptions::class;
            $readerClass = CSVReader::class;
        }

        if ($extension === 'ods') {
            $optionClass = ODSOptions::class;
            $readerClass = ODSReader::class;
        }

        if ($extension === 'xlsx') {
            $optionClass = XLSXOptions::class;
            $readerClass = XLSXReader::class;
        }

        if (! $optionClass || ! $readerClass) {
            throw new UnsupportedTypeException("File extension {$extension} is not supported");
        }

        $options = new $optionClass();
        $options->SHOULD_PRESERVE_EMPTY_ROWS = $this->preserveEmptyRows;

        return new $readerClass($options);
    }

    /**
     * Return the cell value or the computed value if the cell contains a formula.
     */
    protected function getCellValue(Cell $cell): mixed
    {
        if ($cell instanceof FormulaCell) {
            return $cell->getComputedValue();
        }

        return $cell->getValue();
    }
}
