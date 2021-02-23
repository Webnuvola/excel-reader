# Excel Reader
[![Latest Version on Packagist](https://img.shields.io/packagist/v/webnuvola/excel-reader.svg?style=flat-square)](https://packagist.org/packages/webnuvola/excel-reader)
[![Total Downloads](https://img.shields.io/packagist/dt/webnuvola/excel-reader.svg?style=flat-square)](https://packagist.org/packages/webnuvola/excel-reader)

Read spreadsheet files (CSV, XLSX and ODS) using box/spout and return data in array format.

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

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits
- [Fabio Cagliero](https://github.com/fab120)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
