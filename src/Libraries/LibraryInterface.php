<?php

namespace Webnuvola\ExcelReader\Libraries;

interface LibraryInterface
{
    /**
     * Read the file and return data from selected sheet.
     *
     * @param  string $path
     * @param  bool $hasHeaders
     * @param  int|string $sheetId
     * @return array
     */
    public function read(string $path, bool $hasHeaders, $sheetId): array;

    /**
     * Set slugify settings.
     *
     * @param  array $settings
     */
    public function slugify(array $settings): void;
}
