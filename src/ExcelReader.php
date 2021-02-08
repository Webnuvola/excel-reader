<?php

namespace Webnuvola\ExcelReader;

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
     * @var string
     */
    protected string $path;

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
     * @param  string $path
     *
     * @throws \Webnuvola\ExcelReader\Exceptions\LibraryNotFoundException
     */
    public function __construct(string $path)
    {
        $this->library = ExcelReaderManager::resolve();
        $this->path = $path;
    }

    /**
     * Create ExcelReader from file.
     *
     * @param  string $path
     * @return static
     *
     * @throws \Webnuvola\ExcelReader\Exceptions\LibraryNotFoundException
     */
    public static function createFromFile(string $path): self
    {
        return new self($path);
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
     * Read the file and return data from selected sheet.
     *
     * @return array
     */
    public function read(): array
    {
        if (! isset($this->data[$this->sheet])) {
            $this->data[$this->sheet] = $this->library->read($this->path, $this->headers, $this->sheet);
        }

        return $this->data[$this->sheet];
    }
}
