<?php

namespace Webnuvola\ExcelReader\Tests\Unit;

use Webnuvola\ExcelReader\File\FileFactory;
use Webnuvola\ExcelReader\File\FileInterface;
use Webnuvola\ExcelReader\Tests\TestCase;

class FileTest extends TestCase
{
    protected static string $excelContent;

    public static function setUpBeforeClass(): void
    {
        $fp = fopen(__DIR__.'/../resources/financial.xlsx', 'rb');
        self::$excelContent = stream_get_contents($fp);
        fclose($fp);
    }

    /** @test */
    public function create_file_interface_instance()
    {
        $file = FileFactory::createFromPath(__DIR__.'/../resources/financial.xlsx');
        $this->assertInstanceOf(FileInterface::class, $file);

        $file = FileFactory::createFromString(self::$excelContent, 'xlsx');
        $this->assertInstanceOf(FileInterface::class, $file);
    }

    /** @test */
    public function get_methods()
    {
        $file = FileFactory::createFromPath(__DIR__.'/../resources/financial.xlsx');

        $this->assertTrue(file_exists($file->getPath()));
        $this->assertEquals(realpath(__DIR__.'/../resources/financial.xlsx'), $file->getPath());
        $this->assertEquals('xlsx', $file->getExtension());
    }

    /** @test */
    public function temporary_file_is_deleted()
    {
        $file = FileFactory::createFromString(self::$excelContent, 'xlsx');

        $path = $file->getPath();
        $this->assertFileExists($path);

        unset($file);
        $this->assertFileDoesNotExist($path);
    }
}
