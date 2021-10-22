<?php

namespace Webnuvola\ExcelReader\Libraries;

abstract class Library
{
    /**
     * Slugify settings array.
     *
     * @var array
     */
    protected array $slugifySettings = [];

    /**
     * Rows to be skipped.
     *
     * @var int
     */
    protected int $skip = 0;

    /**
     * True if empty rows should be preserved.
     *
     * @var bool
     */
    protected bool $preserveEmptyRows = false;

    /**
     * Set slugify settings.
     *
     * @param  array $settings
     */
    public function slugify(array $settings): void
    {
        $this->slugifySettings = $settings;
    }

    /**
     * Set rows to be skipped.
     *
     * @param  int $rows
     */
    public function skip(int $rows): void
    {
        $this->skip = $rows;
    }

    /**
     * Set if empty rows should be preserved.
     *
     * @param  bool $preserve
     */
    public function preserveEmptyRows(bool $preserve): void
    {
        $this->preserveEmptyRows = $preserve;
    }
}
