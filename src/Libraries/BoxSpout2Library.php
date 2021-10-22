<?php

namespace Webnuvola\ExcelReader\Libraries;

use Box\Spout\Reader\ReaderFactory;
use Cocur\Slugify\Slugify;
use Exception;

class BoxSpout2Library extends Library implements LibraryInterface
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
        $headersCount = 0;
        $filler = [];
        $data = [];

        $first = $hasHeaders;
        $slugify = $hasHeaders ? new Slugify($this->slugifySettings) : null;
        $skipped = 0;

        /** @var \Box\Spout\Reader\SheetInterface $sheet */
        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->$function() !== $sheetId) {
                continue;
            }

            /** @var array $row */
            foreach ($sheet->getRowIterator() as $row) {
                if ($skipped < $this->skip) {
                    $skipped++;
                    continue;
                }

                if ($first) {
                    $headers = array_map(static fn ($cell) => $slugify->slugify($cell), $row);

                    $headersCount = count($headers);
                    $filler = array_fill(0, $headersCount, null);

                    $first = false;
                    continue;
                }

                $currentData = $row + $filler;

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
        return 2;
    }

    /**
     * Set if empty rows should be preserved.
     *
     * @param  bool $preserve
     */
    public function preserveEmptyRows(bool $preserve): void
    {
        throw new Exception('preserveEmptyRows is supported only in box/spout:^3.0');
    }
}
