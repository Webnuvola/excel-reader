<?php

namespace Webnuvola\ExcelReader\Tests\Unit;

use DateTimeImmutable;
use Exception;
use Webnuvola\ExcelReader\ExcelReader;
use Webnuvola\ExcelReader\ExcelReaderManager;
use Webnuvola\ExcelReader\Libraries\OpenSpout4Library;
use Webnuvola\ExcelReader\Tests\TestCase;

it('can instance OpenSpout 4 class', function () {
    expect(ExcelReaderManager::resolve())
        ->toBeInstanceOf(OpenSpout4Library::class);
});

it('can read files with headers', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(700)
        ->and(array_keys($excel[0]))
        ->toBe([
            'segment', 'country', 'product', 'discount-band',
            'units-sold', 'manufacturing-price', 'sale-price', 'gross-sales',
            'discounts', 'sales', 'cogs', 'profit',
            'date', 'month-number', 'month-name', 'year',
        ])
        ->and(array_values($excel[0]))
        ->toEqual([
            'Government', 'Canada', 'Carretera', 'None',
            1618.5, 3, 20, 32370,
            0, 32370, 16185, 16185,
            new DateTimeImmutable('2014-01-01 00:00:00.000000'), 1, 'January', '2014',
        ]);

    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
        ->withHeaders()
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(700)
        ->and(array_keys($excel[0]))
        ->toBe([
            'segment', 'country', 'product', 'discount-band',
            'units-sold', 'manufacturing-price', 'sale-price', 'gross-sales',
            'discounts', 'sales', 'cogs', 'profit',
            'date', 'month-number', 'month-name', 'year',
        ]);

});

it('can read files without headers', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
        ->withoutHeaders()
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(701)
        ->and(array_keys($excel[0]))
        ->toBe(range(0, 15))
        ->and($excel[0])
        ->toBe([
            'Segment', 'Country', 'Product', 'Discount Band',
            'Units Sold', 'Manufacturing Price', 'Sale Price', 'Gross Sales',
            'Discounts', 'Sales', 'COGS', 'Profit',
            'Date', 'Month Number', 'Month Name', 'Year',
        ])
        ->and($excel[1])
        ->toEqual([
            'Government', 'Canada', 'Carretera', 'None',
            1618.5, 3, 20, 32370,
            0, 32370, 16185, 16185,
            new DateTimeImmutable('2014-01-01 00:00:00.000000'), 1, 'January', '2014',
        ]);
});

it('can read sheet number 2', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/users.xlsx')
        ->sheet(1)
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(100)
        ->toBe(
            ExcelReader::createFromPath(__DIR__.'/../resources/users.xlsx')
                ->sheet('Sheet2')
                ->read(),
        )
        ->and(array_keys($excel[0]))
        ->toBe(['first-name', 'last-name', 'gender', 'country', 'age', 'date', 'id'])
        ->and(array_values($excel[0]))
        ->toBe(['Dulce', 'Abril', 'Female', 'United States', 32, '15/10/2017', 1562]);

});

it('can read file from string', function () {
    $excel = ExcelReader::createFromString(financialFile(), 'xlsx')
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(700);
});

it('can read malformed table with headers', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/malformed-table.xlsx')
        ->withHeaders()
        ->read();

    expect($excel)
        ->toBe([
            ['column-a' => 'Value A1', 'column-b' => 'Value B1', 'column-c' => 'Value C1', 'column-d' => 'Value D1'],
            ['column-a' => 'Value A2', 'column-b' => 'Value B2', 'column-c' => 'Value C2', 'column-d' => null],
        ]);
});

it('can read malformed table without headers', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/malformed-table.xlsx')
        ->withoutHeaders()
        ->read();

    expect($excel)
        ->toBe([
            ['Column A', 'Column B', 'Column C', 'Column D'],
            ['Value A1', 'Value B1', 'Value C1', 'Value D1', 'Value E1'],
            ['Value A2', 'Value B2', 'Value C2'],
        ]);
});

it('can skip rows', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/skip.xlsx')
        ->skip(3)
        ->read();

    expect(array_keys($excel[0]))
        ->toBe(['first-name', 'last-name', 'gender', 'country', 'age', 'date', 'id']);
});

it('can preserve empty rows', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/empty-rows.xlsx')
        ->withoutHeaders()
        ->preserveEmptyRows(true)
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(15);

    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/empty-rows.xlsx')
        ->withoutHeaders()
        ->preserveEmptyRows(false)
        ->read();

    expect($excel)
        ->toBeArray()
        ->toHaveCount(6);
});

test('test different slugify settings', function () {
    $excel = ExcelReader::createFromPath(__DIR__.'/../resources/financial.xlsx')
        ->slugify(['separator' => '_'])
        ->read();

    expect(array_keys($excel[0]))
        ->toBe([
            'segment', 'country', 'product', 'discount_band',
            'units_sold', 'manufacturing_price', 'sale_price', 'gross_sales',
            'discounts', 'sales', 'cogs', 'profit',
            'date', 'month_number', 'month_name', 'year',
        ]);
});
