<?php

namespace Webnuvola\ExcelReader\Tests\Unit;

use DateTime;
use Webnuvola\ExcelReader\ExcelReader;
use Webnuvola\ExcelReader\Tests\TestCase;

class ExcelReaderTest extends TestCase
{
    /** @test */
    public function read_file_with_headers()
    {
        $excel = ExcelReader::createFromFile(__DIR__.'/../resources/financial.xlsx')
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
        $excel = ExcelReader::createFromFile(__DIR__.'/../resources/financial.xlsx')
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
        $excel = ExcelReader::createFromFile(__DIR__.'/../resources/financial.xlsx')
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

        $this->assertCount(701, $excel);
    }
}
