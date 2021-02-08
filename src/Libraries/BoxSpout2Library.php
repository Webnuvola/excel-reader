<?php

namespace Webnuvola\ExcelReader\Libraries;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Cocur\Slugify\Slugify;

class BoxSpout2Library implements LibraryInterface
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
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $reader = ReaderFactory::create($extension);
        $reader->open($path);

        $function = is_int($sheetId) ? 'getIndex' : 'getName';

        $headers = [];
        $data = [];

        $slugify = $hasHeaders ? new Slugify() : null;

        /** @var \Box\Spout\Reader\SheetInterface $sheet */
        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->$function() !== $sheetId) {
                continue;
            }

            $first = $hasHeaders;

            /** @var array $row */
            foreach ($sheet->getRowIterator() as $row) {
                if ($first) {
                    $headers = array_map(static fn ($cell) => $slugify->slugify($cell), $row);

                    $first = false;
                    continue;
                }

                $data[] = $row;
            }

            break;
        }

        if (! $hasHeaders) {
            return $data;
        }

        return array_map(static fn ($values) => array_combine($headers, $values), $data);
    }
}
