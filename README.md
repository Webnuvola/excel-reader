# Excel Reader
[![Latest Version on Packagist](https://img.shields.io/packagist/v/webnuvola/excel-reader.svg?style=flat-square)](https://packagist.org/packages/webnuvola/excel-reader)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Webnuvola/excel-reader/run-tests.yml?branch=main)](https://github.com/webnuvola/excel-reader/actions?query=workflow%3ATests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/webnuvola/excel-reader.svg?style=flat-square)](https://packagist.org/packages/webnuvola/excel-reader)

Read spreadsheet files (CSV, XLSX and ODS) using `openspout/openspout` and return data in array format.

## Installation
You can install the package via composer:

```bash
composer require webnuvola/excel-reader
```

## Usage
```php
$excel = \Webnuvola\ExcelReader\ExcelReader::createFromPath(__DIR__.'/excel-file.xlsx')
    ->read();

$excel = \Webnuvola\ExcelReader\ExcelReader::createFromString($content, 'xlsx')
    ->read();
```

## Testing
```bash
composer test
```

## Upgrade Guide

### From `v2` to `v3`
This version drops support for php 7.4 and adds support for php 8.2.

The `openspout/openspout` library is updated to `^4.9`.

### From `v1` to `v2`
Library `box/spout` is replaced with `openspout/openspout`, there are no breaking changes.

If you are using `box/spout` outside this library, please refer to [`openspout/openspout` upgrade guide](https://github.com/openspout/openspout/#upgrade-from-boxspoutv3-to-openspoutopenspoutv3).

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits
- [Fabio Cagliero](https://github.com/fab120)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
