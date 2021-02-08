<?php

namespace Webnuvola\ExcelReader\Libraries;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Cocur\Slugify\Slugify;

class BoxSpout3Library implements LibraryInterface
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
        $data = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->$function() !== $sheetId) {
                continue;
            }

            $slugify = new Slugify();
            $first = $hasHeaders;

            /** @var \Box\Spout\Common\Entity\Row $row */
            foreach ($sheet->getRowIterator() as $row) {
                if ($first) {

                    $headers = array_map(
                        static fn (Cell $cell) => $slugify->slugify($cell->getValue()),
                        $row->getCells(),
                    );

                    $first = false;
                    continue;
                }

                $data[] = array_map(
                    static fn (Cell $cell) => $cell->getValue(),
                    $row->getCells(),
                );
            }

            break;
        }

        if (! $hasHeaders) {
            return $data;
        }

        return array_map(static fn ($values) => array_combine($headers, $values), $data);
    }
}
