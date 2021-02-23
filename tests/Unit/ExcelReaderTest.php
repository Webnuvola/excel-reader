<?php

namespace Webnuvola\ExcelReader\Tests\Unit;

use DateTime;
use RuntimeException;
use Webnuvola\ExcelReader\ExcelReader;
use Webnuvola\ExcelReader\ExcelReaderManager;
use Webnuvola\ExcelReader\Libraries\BoxSpout2Library;
use Webnuvola\ExcelReader\Libraries\BoxSpout3Library;
use Webnuvola\ExcelReader\Tests\TestCase;

class ExcelReaderTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function library()
    {
        $library = ExcelReaderManager::resolve();

        if ($library instanceof BoxSpout3Library) {
            $this->setName('Library box/spout:^3.0');
        } elseif ($library instanceof BoxSpout2Library) {
            $this->setName('Library box/spout:^2.7');
        } else {
            $this->setName('Library not found');
            $this->markTestIncomplete('Library not found');
        }
    }

    /** @test */
    public function read_file_with_headers()
    {
        $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
            ->read();

        $this->assertEquals([
            'segment', 'country', 'product', 'discount-band',
            'units-sold', 'manufacturing-price', 'sale-price', 'gross-sales',
            'discounts', 'sales', 'cogs', 'profit',
            'date', 'month-number', 'month-name', 'year',
        ], array_keys($excel[0]));

        $this->assertEquals([
            'Government', 'Canada', 'Carretera', 'None',
            1618.5, 3, 20, 32370,
            0, 32370, 16185, 16185,
            new DateTime('2014-01-01 00:00:00.000000'), 1, 'January', '2014',
        ], array_values($excel[0]));

        $this->assertCount(700, $excel);
    }

    /** @test */
    public function read_file_with_headers_verbose()
    {
        $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
            ->withHeaders()
            ->read();

        $this->assertEquals([
            'segment', 'country', 'product', 'discount-band',
            'units-sold', 'manufacturing-price', 'sale-price', 'gross-sales',
            'discounts', 'sales', 'cogs', 'profit',
            'date', 'month-number', 'month-name', 'year',
        ], array_keys($excel[0]));
    }

    /** @test */
    public function read_file_without_headers()
    {
        $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
            ->withoutHeaders()
            ->read();

        $this->assertEquals([
            'Segment', 'Country', 'Product', 'Discount Band',
            'Units Sold', 'Manufacturing Price', 'Sale Price', 'Gross Sales',
            'Discounts', 'Sales', 'COGS', 'Profit',
            'Date', 'Month Number', 'Month Name', 'Year',
        ], $excel[0]);

        $this->assertEquals([
            'Government', 'Canada', 'Carretera', 'None',
            1618.5, 3, 20, 32370,
            0, 32370, 16185, 16185,
            new DateTime('2014-01-01 00:00:00.000000'), 1, 'January', '2014',
        ], $excel[1]);

        $this->assertEquals(range(0, 15), array_keys($excel[0]));

        $this->assertCount(701, $excel);
    }

    /** @test */
    public function read_second_sheet()
    {
        $excelReader = ExcelReader::createFromPath(__DIR__.'/../resources/users.xlsx');

        $sheet1 = $excelReader->read();
        $this->assertEquals([], $sheet1);

        $sheet2 = $excelReader->sheet(1)->read();

        $this->assertEquals([
            'first-name', 'last-name', 'gender', 'country',
            'age', 'date', 'id',
        ], array_keys($sheet2[0]));

        $this->assertEquals([
            'Dulce', 'Abril', 'Female', 'United States',
            32, '15/10/2017', 1562,
        ], array_values($sheet2[0]));

        $this->assertCount(100, $sheet2);

        $sheetByName = ExcelReader::createFromPath(__DIR__.'/../resources/users.xlsx')
            ->sheet('Sheet2')
            ->read();

        $this->assertEquals($sheet2, $sheetByName);
    }

    /** @test */
    public function read_file_from_string()
    {
        $fp = fopen(__DIR__.'/../resources/financial.xlsx', 'rb');
        $content = stream_get_contents($fp);
        fclose($fp);

        $excel = ExcelReader::createFromString($content, 'xlsx')
            ->read();

        $this->assertCount(700, $excel);
    }

    /** @test */
    public function malformed_table_with_headers()
    {
        $excel = ExcelReader::createFromPath(__DIR__.'/../resources/malformed-table.xlsx')
            ->read();

        $this->assertEquals([
            ['column-a' => 'Value A1', 'column-b' => 'Value B1', 'column-c' => 'Value C1', 'column-d' => 'Value D1'],
            ['column-a' => 'Value A2', 'column-b' => 'Value B2', 'column-c' => 'Value C2', 'column-d' => null],
        ], $excel);
    }

    /** @test */
    public function malformed_table_without_headers()
    {
        $excel = ExcelReader::createFromPath(__DIR__.'/../resources/malformed-table.xlsx')
            ->withoutHeaders()
            ->read();

        $this->assertEquals([
            ['Column A', 'Column B', 'Column C', 'Column D'],
            ['Value A1', 'Value B1', 'Value C1', 'Value D1', 'Value E1'],
            ['Value A2', 'Value B2', 'Value C2'],
        ], $excel);
    }
}
