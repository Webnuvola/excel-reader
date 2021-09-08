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
     * Set slugify settings.
     *
     * @param  array $settings
     */
    public function slugify(array $settings): void
    {
        $this->slugifySettings = $settings;
    }
}
