<?php

namespace Webnuvola\ExcelReader\Libraries;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Reader\Common\Creator\ReaderFactory;
use Cocur\Slugify\Slugify;

class OpenSpout3Library extends Library implements LibraryInterface
{
    /**
     * Read the file and return data from selected sheet.
     *
     * @param  string $path
     * @param  bool $hasHeaders
     * @param  int|string $sheetId
     * @return array
     *
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     */
    public function read(string $path, bool $hasHeaders, $sheetId): array
    {
        $reader = ReaderFactory::createFromFile($path);
        $reader->setShouldPreserveEmptyRows($this->preserveEmptyRows);
        $reader->open($path);

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
                        static fn (Cell $cell) => $slugify->slugify($cell->getValue()),
                        $row->getCells(),
                    );

                    $headersCount = count($headers);
                    $filler = array_fill(0, $headersCount, null);

                    $first = false;
                    continue;
                }

                $currentData = array_map(
                        static fn (Cell $cell) => $cell->getValue(),
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
        return 3;
    }
}
