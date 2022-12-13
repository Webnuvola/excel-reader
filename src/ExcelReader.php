<?php

namespace Webnuvola\ExcelReader;

use Webnuvola\ExcelReader\File\FileFactory;
use Webnuvola\ExcelReader\File\FileInterface;
use Webnuvola\ExcelReader\Libraries\LibraryInterface;

final class ExcelReader
{
    /**
     * Library interface.
     *
     * @var \Webnuvola\ExcelReader\Libraries\LibraryInterface
     */
    protected LibraryInterface $library;

    /**
     * Excel file path.
     *
     * @var \Webnuvola\ExcelReader\File\FileInterface
     */
    protected FileInterface $file;

    /**
     * First row as headers.
     *
     * @var bool
     */
    protected bool $headers = true;

    /**
     * Sheet number or name.
     *
     * @var int|string
     */
    protected $sheet = 0;

    /**
     * Excel file data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * ExcelReader constructor.
     *
     * @param  \Webnuvola\ExcelReader\File\FileInterface $file
     */
    public function __construct(FileInterface $file)
    {
        $this->file = $file;
        $this->library = ExcelReaderManager::resolve();
    }

    /**
     * Create ExcelReader from path.
     *
     * @param  string $path
     * @return static
     */
    public static function createFromPath(string $path): self
    {
        return new self(FileFactory::createFromPath($path));
    }

    /**
     * Create ExcelReader from string.
     *
     * @param  string $content
     * @param  string $extension
     * @return static
     */
    public static function createFromString(string $content, string $extension): self
    {
        return new self(FileFactory::createFromString($content, $extension));
    }

    /**
     * Return library version.
     *
     * @return int
     */
    public static function version(): int
    {
        return ExcelReaderManager::resolve()->version();
    }

    /**
     * Set first row as header.
     *
     * @param  bool $value
     * @return $this
     */
    public function headers(bool $value): self
    {
        $this->headers = $value;

        return $this;
    }

    /**
     * First row is headers.
     *
     * @return $this
     */
    public function withHeaders(): self
    {
        $this->headers(true);

        return $this;
    }

    /**
     * First row is not headers.
     *
     * @return $this
     */
    public function withoutHeaders(): self
    {
        $this->headers(false);

        return $this;
    }

    /**
     * Sheet to read.
     *
     * @param  int|string $sheet
     * @return $this
     */
    public function sheet($sheet): self
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * Set slugify settings.
     *
     * @param  array $settings
     * @return $this
     */
    public function slugify(array $settings): self
    {
        $this->library->slugify($settings);

        return $this;
    }

    /**
     * Set rows to be skipped.
     *
     * @param  int $rows
     * @return $this
     */
    public function skip(int $rows): self
    {
        $this->library->skip($rows);

        return $this;
    }

    /**
     * Set if empty rows should be preserved.
     *
     * @param  bool $preserve
     * @return $this
     */
    public function preserveEmptyRows(bool $preserve): self
    {
        $this->library->preserveEmptyRows($preserve);

        return $this;
    }

    /**
     * Read the file and return data from selected sheet.
     *
     * @return array
     */
    public function read(): array
    {
        if (! isset($this->data[$this->sheet])) {
            $this->data[$this->sheet] = $this->library->read($this->file, $this->headers, $this->sheet);
        }

        return $this->data[$this->sheet];
    }
}
