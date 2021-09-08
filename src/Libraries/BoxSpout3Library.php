<?php

namespace Webnuvola\ExcelReader\Libraries;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Cocur\Slugify\Slugify;

class BoxSpout3Library extends Library implements LibraryInterface
{
    /**
     * Read the file and return data from selected sheet.
     *
     * @param  string $path
     * @param  bool $hasHeaders
     * @param  int|string $sheetId
     * @return array
     *
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function read(string $path, bool $hasHeaders, $sheetId): array
    {
        $reader = ReaderFactory::createFromFile($path);
        $reader->open($path);

        $function = is_int($sheetId) ? 'getIndex' : 'getName';

        $headers = [];
        $headersCount = 0;
        $filler = [];
        $data = [];

        $slugify = $hasHeaders ? new Slugify($this->slugifySettings) : null;

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->$function() !== $sheetId) {
                continue;
            }

            $first = $hasHeaders;

            /** @var \Box\Spout\Common\Entity\Row $row */
            foreach ($sheet->getRowIterator() as $row) {
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
}
